# KolayIK Hiring Challenge

## The Kriptoken Trading Platform 
### Introduction 
A digital currency trading platform called Kriptoken will be developed. There are 5 currencies on the platform, such as ETC, LTC, ETH, DASH, XRP. These currencies can be exchanged between them. The current prices are as follows and will not change. 

| Currency | Value |
| ------------- |:----------:|
| 1 ETH | 0.04791411 BTC |
| 1 LTC | 0.00592855 BTC |
| 1 DASH | 0.06756612 BTC |
| 1 XRP | 0.00002001 BTC |
 
### Requirements 
- Users can be registered only by name, surname, e-mail address, and password are accepted for the registration. Email sending and verification are not required 
- 10000 XRP will be given to each newly registered user
- The user will be able to buy another currency by selling any of the currencies in his account. 0.25% trading commission will be charged for each transaction
- Users mill be able to list the currencies in their account 
- Users mill be able to list the current values of their currencies 

### Technical Information 
- A RESTful API that can be authenticated with e-mail and password will be developed
- Any PHP framework can be used but Laravel is a plus 
- Codes should run on PHP 7.3 
- Any database can be used 
- Responses of endpoints and naming can be done according to your own standards
- Every endpoint should have e2e tests
- Every function should have unit tests 

#### As a result, there should be five endpoints that perform the following tasks: 
- Register
- Login/Auth 
- Buy
- Show account information 
- List all the currencies 

### Objectives: 
- The API does not require any interface
- The code must be opened in this repo as PR 
- Code quality, framework dominance, perforrance, commit messages and in-code documentation will be highly considered 

### Bonus: 
- The project must be running on [HerokuMhttps://devcenter.heroku.com/articles/getting-started-with-phpaintroduCtiOn) 
