Symfony 5 on Docker
==========
This is a boilerplate for Symfony 5 on Docker.
It also contains a small api example built on Symfony 5 and PHP 7.4.
Please keep in mind that this is a work in progress, and it will be improved 
in the future. If you have any suggestions please let me know, or just do it yourself.
Thanks.

In `postman-test-collection` directory, one can find the api collection to import in Postman 
application for testing purposes.

#### Structure
- bin, config, public, src & test directories are Symfony framework related 
- nginx, php directories are Docker related
- postman-test-collection contains the collections to be imported in Postman application

#### Todo
- Fix the issue with main and master branches
- Rename api collection
- Group all directories related to the app into a directory called app
- Group all directory related to Docker into one called docker
- Add https support
- Complete the README.md file

### :electric_plug: Dependencies
You need Docker in order to use the app as it was intended.
The project should run in your machine environment if all dependencies are meet.

### :memo: Documentation
Run the project then use Postman application to make requests.
In `postman-test-collection` directory, one can find the api collection to import in Postman 
application for testing purposes.

To run the project, go to the project root directory and run the following 
command:  
`docker-compose up -d --build`
After that you can access the test api on `http:localhost:8080/users`.
### :scroll: License
See the [LICENSE](LICENSE.md) file for license rights and limitations.
