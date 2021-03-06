<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\Bridges\DatabaseTracy;

use Nette;
use Nette\Database\Connection;
use Nette\Database\Helpers;
use Tracy;


/**
 * Debug panel for Nette\Database.
 */
class ConnectionPanel implements Tracy\IBarPanel
{
	use Nette\SmartObject;

	/** @var int */
	public $maxQueries = 100;

	/** @var string */
	public $name;

	/** @var bool|string explain queries? */
	public $explain = true;

	/** @var bool */
	public $disabled = false;

	/** @var float logged time */
	private $totalTime = 0;

	/** @var int */
	private $count = 0;

	/** @var array */
	private $queries = [];


	public function __construct(Connection $connection)
	{
		$connection->onQuery[] = [$this, 'logQuery'];
	}


	public function logQuery(Connection $connection, $result): void
	{
		if ($this->disabled) {
			return;
		}
		$this->count++;

		$source = null;
		$trace = $result instanceof \PDOException ? $result->getTrace() : debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		foreach ($trace as $row) {
			if (
				(isset($row['file']) && is_file($row['file']) && !Tracy\Debugger::getBluescreen()->isCollapsed($row['file']))
				&& ($row['class'] ?? '') !== self::class
				&& !is_a($row['class'] ?? '', Connection::class, true)
			) {
				$source = [$row['file'], (int) $row['line']];
				break;
			}
		}
		if ($result instanceof Nette\Database\ResultSet) {
			$this->totalTime += $result->getTime();
			if ($this->count < $this->maxQueries) {
				$this->queries[] = [$connection, $result->getQueryString(), $result->getParameters(), $source, $result->getTime(), $result->getRowCount(), null];
			}

		} elseif ($result instanceof \PDOException && $this->count < $this->maxQueries) {
			$this->queries[] = [$connection, $result->queryString, null, $source, null, null, $result->getMessage()];
		}
	}


	public static function renderException(?\Throwable $e): ?array
	{
		if (!$e instanceof \PDOException) {
			return null;
		}
		if (isset($e->queryString)) {
			$sql = $e->queryString;

		} elseif ($item = Tracy\Helpers::findTrace($e->getTrace(), 'PDO::prepare')) {
			$sql = $item['args'][0];
		}
		return isset($sql) ? [
			'tab' => 'SQL',
			'panel' => Helpers::dumpSql($sql, $e->params ?? []),
		] : null;
	}


	public function getTab(): string
	{
		$name = $this->name;
		$count = $this->count;
		$totalTime = $this->totalTime;
		ob_start(function () {});
		require __DIR__ . '/templates/ConnectionPanel.tab.phtml';
		return ob_get_clean();
	}


	public function getPanel(): ?string
	{
		$this->disabled = true;
		if (!$this->count) {
			return null;
		}

		$name = $this->name;
		$count = $this->count;
		$totalTime = $this->totalTime;
		$queries = [];
		foreach ($this->queries as $query) {
			[$connection, $sql, , , , , $error] = $query;
			$explain = null;
			$command = preg_match('#\s*\(?\s*(SELECT|INSERT|UPDATE|DELETE)\s#iA', $sql, $m) ? strtolower($m[1]) : null;
			if (!$error && $this->explain && $command === 'select') {
				try {
					$cmd = is_string($this->explain) ? $this->explain : 'EXPLAIN';
					$explain = $connection->queryArgs("$cmd $sql", [])->fetchAll();
				} catch (\PDOException $e) {
				}
			}
			$query[] = $command;
			$query[] = $explain;
			$queries[] = $query;
		}

		ob_start(function () {});
		require __DIR__ . '/templates/ConnectionPanel.panel.phtml';
		return ob_get_clean();
	}
}
