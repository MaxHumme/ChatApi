# Chat Api

Hello reviewer, and welcome. This is my version of the bunq Software Developer Assignment. You can read this document to get a better understanding of how I interpreted the assignment and what I’m doing in the code and why. Skip down to Api manual and how to fire it up to see it in action, or skip everything and go down to `How to fire it up?` if you don’t like manuals in general. Who needs them anyway ;)

## Interpretation
The interpretation of this assignment is that I’m asked to implement the first two endpoints of a stateless api that will become larger and more complex in the future. The api can be used by an app to let users chat with each other. A user can send a message to another user, and a user can fetch his or her messages. So when Trump wants to send a message to Hillary, and Hillary wants to fetch and read her messages, they can use any app that uses this api. (And we’ll be reading those messages along in the meantime of course. Don’t tell.)

Data will be stored in a SQLite database. We communicate with the api by sending and receiving JSON messages over HTTP. There is no user interface (it’s an api!) and we’re not doing registration and login functionality. Authenticating a user is handled through a token, unique for that user, that will be sent with every request.

So much for the interpretation. I hope you agree. Let’s get on with:

## Frameworks, libraries and other decisions
### Lumen
Lumen 5.3 is the web app framework of choice. It’s Laravel stripped down and tuned for performance. It depends on Symfony packages and it arranges everything around receiving a request and sending the response. It gives us an ORM (Eloquent) and it gives us a powerful Service Container that does the dependency injection. I’m not as familiar with it as I am with Laravel, but I think it fits nicely in this assignment and it gives me a change to check it out.

### Composer
Composer is the PHP dependency manager and autoloader. You can find all other PHP dependencies in the `composer.json` file in the root of the project.

### PHP 7
PHP 7 it is. Because it’s the newest thing, and because it’s faster.

### PSR-2
Code style wise I’m doing PSR-2.
## Architecture
### Onion architecture
For this api I’m using the so called [Onion architecture](http://jeffreypalermo.com/blog/the-onion-architecture-part-3/) to keep coupling low. This means the domain is the center of it all. The domain layer is wrapped by the infrastructure (e.g. persistence), http and test layers. The rule for the layers is that they can only depend on layers they’re not wrapped by. The infrastructure, http and test layer are sharing the same level, so they are allowed to depend on each other. This means that changes in the http or infrastructure layer don’t ripple through to the application and domain layer.

### Domain Driven Design
I’m going the Domain Driven Design route for this assignment. It is not the quickest way to a nice result, for from it, but I want to have some code to work with to give you a good idea of where I’m at. Besides that, I think it is a *SOLID* way of building an application and it should at least be considered for every new project.

If I wasn’t to go domain driven and wanted to have a quick result, I would create a simple UserRepository which queries our database for Users (ORM objects) and a MessageRepository which queries for Messages (ORM objects as well). To keep things easy, the repositories won’t keep the fetched Users and Messages in memory might we need them again later in the request. There would be a MessageController to control the incoming requests, to send the actions to perform off to a MessageService and to format and return the response. The MessageService would handle sending a message and fetching messages for the user and would only depend on the UserRepository and MessageRepository. But I like to show you a little more than that, so domain driven it is!

Domain Driven Design implicates a lot of different things. Most importantly it means there is a domain layer which contains all the business logic. To implement the specification of this assignment, there is not much logic to implement, so this seems overly complex. But the domain entities and relations itself tell us something about the problem we’re solving. And considering the spec says this api should be prepared for a lot of endpoints, it would grow and become more complicated over time. The domain creates a nice place to put all current and future needed business logic.

### Restful
The api will be RESTful. I’m doing a little [HATEOAS](https://en.wikipedia.org/wiki/HATEOAS) on the send message response. And in the RESTful spirit we will treat the endpoint urls as web resource locations. The responses are not cacheable, so the api will define them as such.

## The layers and what’s in it
Here I’ll list the most important classes that appear in the different layers. It is not a comprehensive list and is written up to help in interpreting what happens in the code.

### Domain layer and its entities
In real life, users probably want to be able to send a message to multiple users or a group. The spec tells us a user should be able to send another user (singular) a message. With a bit more context behind the assignment the decision could be to prepare the domain for that. It would not break the current spec and would make the api a bit more future proof. But I don’t have that context, so I’m holding on to the spec and only implement one user sending a message to another user.

#### Message
The message entity. It has the message body and the date and time it was sent and knows about the sender and recipient Persons that have to do with it.

#### Person
The Person entity. The Message knows which Person sent the Message to which other Person. It has name and username attributes.

#### MessageFactory
Is responsible for creating new Messages and bringing persisted Messages back to life. It is mainly created to relieve the MessageRepository from that responsibility.

#### ActorFactory
And the ActorFactory reconstitutes Senders and Recipients in the same way as MessageFactory does that for Messages.

#### Interfaces/Contracts
Apart from the domain’s own contracts, it defines the MessageRepository and PersonRepository contracts. These will be implemented by the Infrasctructure layer, because these classes will have to know about how to store their entities in our database.

### Http layer and its classes
#### MessageController
The MessageController is responsible for directing the processes around the message resource. It’s where the request comes in, the calls to the domain layer are done and the response goes out. It’s a pretty standard controller. You can find the routes this controller responds to in `routes/api.php`.

#### Formatters
These classes format the domain entities into the response we want.

#### JsonResponseHandler
Makes sure we send JSON.

#### Middleware
Is loaded before every request (arranged by Laravel/Lumen). We have an Authenticate class that checks if a user is authenticated, and we have a JsonRequest class to make sure our api receives JSON.

### Infrastructure layer and its classes
#### MessageRepository
The MessageRepository acts like a collection of Messages (the domain entity, I use the capital M to hint that I’m talking about the entity object). It encapsulates storing and retrieving Messages from our data store so the domain is not bothered with it. We can ask it for the Messages we want and we can add Messages to it. It stores the retrieved Messages in memory during the request, so we spare us a query when we need them again later in the same request.

#### PersonRepository
The PersonRepository is responsible for storing and retrieving Persons from persistence.

#### Message
The Eloquent Message object (Eloquent is Lumen’s ORM). It is connected to the message table in our SQLite database. 
Why don’t I use the Eloquent Message object as a domain entity? Because it would couple our infrastructure to our domain. Which means we might have to rewrite the domain when we choose a different database technology or we make changes in our data structure. We don’t want that in an api that could become much more complicated.

#### User
The Eloquent User object. It is connected to the user table.

### The Framework folder
This folder has some stuff the Lumen framework needs in order to work.
* `BehatExtension` is a Laravel package I had to import to be able to tweak it for usage with Lumen. I need it to run Lumen in my Behat specifications.
* `Console` is where the Lumen console kernel lives.
* `Exceptions` has the handler that comes with Lumen that I tweaked to make sure we give JSON error responses (except when debugging).
* `Providers` has the providers that are loaded when Lumen is booted. They bind some contracts to implementations so the Service container knows which class to inject.

## Database
This database has two tables (see the database for more info). I am using the username as the business key. This will be the unique key we use in our resource urls. The created_at and updated_at fields in the message table are added and updated automatically by Lumen. We don’t need the updated_at field for this spec, but it comes with created_at (which we’ll use to store the date and time on which the message was sent).

### The user table
Which stores the following:
id, username (business key, unique), first_name, last_name, auth_token

### The message table
Which has the following columns:
id, from_user_id, to_user_id, body, created_at, updated_at

## Tests
Doing this thing domain driven, does not come for free. Like everyone else I am time limited, so there has to be a trade-off somewhere. And here it is. I have created the most important acceptance tests and one unit test/specification to at least show you I am no stranger to testing.

### Acceptance tests
Behat is used to test the feature specifications of this api. Because this is a tiny api, they function as integration tests as well. You can find the specifications in the `features` folder. Make sure the api is accessible at `http://localhost`. I did not set Behat up to function on a different url. Run `vendor/bin/behat` from the root of the project to run the acceptance tests. Pleas run `php artisan migrate:refresh --seed` afterwards, if you want to fiddle with the api again, otherwise you’re left with Behat’s test data.

### Unit tests
I use PHPSpec to describe the behavior of my classes. They double as unit tests. There is only one however in this project. You can find the spec under `specs`. Run `vendor/bin/phpspec run --format pretty` from the root of the project to run the test.

## Api manual
### Headers
#### Authorization
The client should add the auth_token of the user it wants to authenticate with to the header of every request. Use the `Authorization` header field for this, per [RFC 2616](https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html). Invalid authentication gives a response with HTTP status code 401 (Unauthorized).

#### Content-Type
The `Content-Type` header should be set to `application/json` for every post and put request.

### End points
#### Sending messages
##### Method and url
POST http://domain.name/api/v1/{username}/messages

##### Url elements
* username: The username of the user to send the message to.

##### Request body data
* message: The body of the message.

For example: `{"message": “Hi there!”}`

##### Response
The response for a successful request will return HTTP status 201 with the url to the newly created resource (which is stale, because I was not asked to implement it). An unsuccessful request will return an error code with some more info in the body.

#### Fetching messages
##### Method and url
GET http://domain.name/api/v1/{username}/messages

##### Url elements
* username: The username of the user to fetch the messages from.

##### Request parameters
* offset: (optional) An integer that defines the index of the first item to return, where 0 is the oldest message. When not set, 0 is assumed. 
* limit: (optional) An integer that defines the number of messages to get. When not set, 20 is assumed. Min is 1, max is 50.

##### Response
The response for a successful request will return HTTP status 200 (OK) with an array of message objects. An unsuccessful request will return an error code with some more info in the body.

##### Message object
* index: the index of the message, the first one being 1
* author: A string with the authors name.
* body: A string with the message body.
* sentAt: the date and time the message was sent in YYYY-mm-dd HH:ii:ss format


For example:
`{messages: [{"index": 1, "author": “Hannibal”, "body": “I love it when a plan comes together.”, "sentAt": “1984-11-16 16:14:43”}]}`

## How to fire it up?
You need a computer. It may be virtual. But it needs to have the following installed:

### Requirements
* PHP7
* SQLite
* Nginx/Apache or something similar
* Composer

### Install
Clone this repo and run `composer install` in the root of the project.

### Create the database
Create a SQLite database on your machine. The `database` folder in the root of the project is a good place for it. Edit the `.env` and set `DB_DATABASE` to the absolute file path of that database.

### Migrate and seed the database
To migrate and fill the database, run `php artisan migrate --seed` in the root of the project. It will give you some users (with tokens) and messages to work with. Check the database for details. Run `php artisan migrate:refresh --seed` if you want to recreate the database.

### See it in action
Make sure the virtual host maps to `public/index.php`. Send the requests (see `Api manual` above)  with PhpStorm, Postman or something similar. Or maybe with a configurable web frontend/app you had designed in a Frontend Developer Assignment ;)
