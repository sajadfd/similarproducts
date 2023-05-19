 ## Run project
1.Clone the project <br />
2.Go to the folder application using cd command on your cmd or terminal <br />
3.Run composer install on your cmd or terminal <br />
4.Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal, Ubuntu <br />
5.Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration. <br />
6.Run php artisan key:generate<br />
7.php artisan migrate:refresh --seed You can change the number in file ProductsTableSeeder.php in folder  \database\seeders\ProductsTableSeeder.php <br />
8.Run php artisan serve <br />
9.Go to http://127.0.0.1:8000/products/1


 ## Note:
If you want to test the code with two million (2,000,000) products, you can import the database file attached to the project under the name (likeproducts_db.sql) and execute it in your database <br />
If you do, you don't need to do step #7!
