The project is an integration with the Blizzard API service allowing users to review their characters and character's achievments and stats.
Users authorise the client to make calls on their behalf using Oauth2 authentication to generate access tokens.
The project demonstrates the usage of the following core Laravel features:
1. Form requests
2. Usage of the HTTP facade wrapper for Guzzle
3. Feature tests of methods creating API calls as well as methods returning results to the user through the interface
4. Predis facade for Redis interaction. Used for caching API results.
5. Observers - used to notify admins when a user deletes their profile (soft delete)
6. Jobs - used to notify admins when a user registers or deletes their profile
7. Middleware - used to limit access to certain routes
8. Route model binding - User routes
9. Dependancy injection - Warcraft Controller
10. Login scaffolding is created via Laravel breeze.

Future plans 
1. Extend available character methods/data
2. Add support for Diablo 3 characters
3. Improve front end - vuejs?
4. Add logging other than laravel.log for errors
