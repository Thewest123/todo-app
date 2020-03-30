# Todo App
Made with Vue.JS + Nette API

![Main screen](https://i.imgur.com/NqGqHQT.png)

## How to install
 1. Import "**todos_db.sql**" into your database
 
 2. Move the "**todo-app**" folder to your web server (htdocs folder)
	 1. Change settings in `todo-app\app\config\local.neon` to point to your database
         
 3. Navigate to the "**todo-front**" folder
	 1. Change the `VUE_APP_BACKEND_URL` path in the "**.env**" file to point to `<YOUR_WEB_URL>/todo-app/www/todo` on your web server
	 2. `yarn`
	 3. `yarn serve`

## Demo
[Demo Link](https://work.jancerny.dev/todo)
