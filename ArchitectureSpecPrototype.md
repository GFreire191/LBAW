


# EAP: Architecture Specification and Prototype

#    A7: Web Resources Specification

This artifact documents the  architecture of the web application to be developed, indicating the catalog of resources, the properties of each resource, and the format of JSON responses. This specification adheres to the OpenAPI standard using YAML.


This artifact presents the documentation for StackUnderFlow, including the CRUD (create, read, update, delete) operations for each resource implemented in the vertical prototype.

## 1.Overview

|    Module    |  Specification  |
|--------------|-----------------|
|M01: Authentication and Individual Profile|Web resources associated with user authentication and individual profile management. Includes the following system features: login/logout, registration, credential recovery, view and edit personal profile information.|
|M02: Questions| Web resources associated with questions. Includes the following system features: add question, edit question and delete question.|
|M03: Answers|Web resources associated with answers. Includes the following system features: add answer, edit answer and delete answer.|
|M04: Comments|Web resources associated with comments. Includes the following system features: add comment, edit comment and delete comment.|
|M05: User Administration and Static pages|Web resources associated with user management, specifically: delete user accounts and view system access details for each user. Web resources with static content are associated with this module: homepage, about, contact, services and faq.|
|M06: Searches|Web resources associated with the search functionality for the questions and users|

## 2.Permissions

This section defines the permissions used in the modules to establish the conditions of access to resources.

|          |          |          |
|----------|----------|----------|
|**PUB**|Public|Users without privileges|
|**User**|User|Authenticated users|
|**Owner**|Owner|Users that are owners of the information(e.g own profile, own posts, own votes,own notification)|
|**MDT**|Moderator|Delete content, Edit questions Tags|
|**ADM**|Administrator|Manage Tags|



## 3.OpenAPI Specification

Link to specification: https://git.fe.up.pt/lbaw/lbaw2324/lbaw23153/-/blob/main/a7_openapi.yaml?ref_type=heads

~~~yaml
openapi: 3.0.0
info:
    version: '1.0'
    title: 'LBAW StackUnderFlow Web API'
    description: 'Web resources specification (A7) for StackUnderFlow'

servers:
    - url: 
      description: Production server

externalDocs: 
    description: Find more info here.
    url: 

tags: 
    - name: 'M01: Authentication and  Individual Profile'
    - name: 'M02: Questions'
    - name: 'M03: Answers'
    - name: 'M04: Comments'
    - name: 'M05: User Administration and Static pages'
    - name: 'M06: Searches'

paths:
    #------------------M01-------------------
    #login
    /login:
        get:
          operationId: R101
          summary: 'R101: Login Form'
          description: 'Provide the form for the user login.'
          tags:
            - 'M01: Authentication and  Individual Profile'
          responses:
              '200':
                  description: 'Went well. Show the form UI.'

        post:
            operationId: R102
            summary: 'R102: Login Action'
            description: 'Login to the website.'
            tags:
              - 'M01: Authentication and  Individual Profile'

            requestBody:
                required: True
                content:
                    application/x-www-form-urlencoded:
                        schema:
                            type: object
                            properties:
                                password:
                                    type: string
                                email:
                                    type: string
                            required:
                                - inputPassword
                                - inputEmail
            responses:
                '302':
                    description: 'Redirect after login'
                    headers:
                      Location:
                        schema:
                          type: string
                        examples:
                          302Success:
                            description: 'Ok. Redirect to Home Page'
                            value: '/'
                          302Error:
                            description: 'Failed Authentication. Redirect to login form.'
                            value: '/login'
    /logout:
      post:
        operationId: R102
        summary: 'R102: Logout Action'
        description: 'Logout the current authenticated user.'
        tags:
          - 'M01: Authentication and  Individual Profile'
        responses:
          '302':
            description: 'Redirect after processing logout.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successful logout. Redirect to homepage.'
                    value: '/'
    /register:
      get:
        operationId: R104
        summary: 'R104: Signup Form'
        description: 'Provide Signup form.'
        tags:
          - 'M01: Authentication and  Individual Profile'
        responses:
          '200':
            description: 'Went well. Show signup form.'
      post:
        operationId: R105
        summary: 'R105: Signup Action'
        description: 'Signup to the website'
        tags:
          - 'M01: Authentication and  Individual Profile'
        requestBody:
          required: True
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  name:
                    type: string
                  password:
                    type: string
                  email:
                    type: string
                required:
                  - name
                  - password
                  - email
        responses:
          '302':
            description: 'Redirect after processing signup credentials.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successful credential insertion. Redirect to home page.'
                    value: '/'
                  302Error:
                    description: 'Failed credential insertion. Redirect to signup form'
                    value: '/register'

    /user/{id}/profile:
      get:
        operationId: R106
        summary: 'R106: View user Profile'
        description: 'Show the individual user profile'
        tags:
          - 'M01: Authentication and  Individual Profile'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '200':
            description: 'Ok. Show user profile'
      post:
        operationId: R107
        summary: 'R107: Edit Profile'
        description: 'Process the new user edition form submission.'
        tags: 
          - 'M01: Authentication and  Individual Profile'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
              required: True
        requestBody:
          required: True
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  name:
                    type: string
                  picture:
                    type: string
                  email:
                    type: string
        responses:
          '302':
            description: 'Redirect after processing the user new information.'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successful profile edition. Redirect to user profile.'
                    value: '/users/{id}/profile'
                  302Failure:
                    description: 'Failed to edit. Redirect to user profile.'
                    value: '/users/{id}/profile'
      /users/{id}/profile/edit:
        get: 
            operationId: R107
            summary: 'R107: View user profile edit form'
            description: 'Show the individual user profile edition form. Access: USR'
            tags:
                - 'M01: Authentication and Profile'

            parameters:
              - in: path
                name: id
                schema:
                    type: integer
                required: true

            responses:
                '200':
                  description: 'Ok. Show profile edition form UI.'

#------------------ M02 --------------------

    /question/create:
      get:
        operationId: R201
        summary: 'R201: Get the question creation form.'
        description: 'Show the question creation form to the User'
        tags:
          - 'M02: Questions'
        responses:
          '200':  
            description: 'Ok. Show Create Question Form UI.'
    /question:
      post:
        operationId: R202
        summary: 'R202: Create Questions'
        description: 'Process the Create Question Form submission.'
        tags:
          - 'M02: Questions'
        requestBody:
          required: true
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                    title:
                      type: string
                    content:
                      type: string
        responses:
          '302':
            description: 'Redirect after the question has been created'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successful question creation'
                    value: '/home'
                  302Failure:
                    description: 'Failed to create the question. Redirect to homepage.'
                    value: '/home'

    /question/{question}/show:
      get:
        operationId: R203
        summary: 'R203: Get the question to show'
        description: 'Show the individual page for a question with it''s information'
        tags:
          # - 'M02: Questions'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '200':
            description: 'Ok. Show Question Page'

    /question/{question}/edit:
      get:
        operationId: R204
        summary: 'R204: Get the form to edit a Question'
        description: 'Turn the question field into editable areas'
        tags:
          - 'M02: Questions'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '200':
            description: 'Ok. Change the fields into editable'
      put:
        operationId: R205
        summary: 'R205: Edit the question'
        description: 'Process the new information and update the question'
        tags:
          - 'M02: Questions'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        requestBody:
          required: True
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  title:
                    type: string
                  content:
                    type: string
                required:
                  - title
                  - content
        responses:
          '302':
            description: 'Redirect after updating question information'
            headers:
              Location:
                schema:
                  type: string
                examples: 
                  302Success:
                    description: 'Successfully Updated the question. Redirect to home Page'
                    value: '/home'
                  302Failure:
                    description: 'Failed to edit and update the question'
                    value: '/home'
    /question/{question}/delete:
      delete:
        operationId: R206
        summary: 'R206: Delete a question'
        description: 'Process the deletion of a question.'
        tags:
         - 'M02: Questions'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '302':
            description: 'Redirect user after deleting a question'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successfully deleted. Redirect to homepage.'
                    value: '/home'
                  302Failure:
                    description: 'Failed to delete the question. Redirect to homepage'
                    value: '/home'
    /top_questions:
      get:
        operationId: R207
        summary: 'R207: View Top Questions'
        description: 'Show the top questions in the database'
        tags:
         - 'M02: Questions'
        responses:
          '200':
            description: 'Ok. Show top Questions'
    
#-------------- M03 -----------------------------------------

    /question/{question}/answer:
      post:
        operationId: R301
        summary: 'R301: Create an answer'
        description: 'Process the answer form and create the answer'
        tags:
          - 'M03: Answers'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True

        requestBody:
          required: True
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  content:
                    type: string
                required:
                  - content
        responses:
          '302':
            description: 'Redirect after creating the new Answer'
            headers:
              Location:
                schema: 
                  type: string
                examples:
                  302Success:
                    description: 'Successfully created an answer. Redirect to Question Page'
                    value: '/question/{question}/show'
                  302Failure:
                    description: 'Failed to create an answer. Redirect to the question page'
                    value: '/question/{question}/show'
    /answer/{answer}/show:
      get:
        operationId: R302
        summary: 'R302: Show the answer'
        description: 'Show the question page relative to that answer, with that answer'
        tags:
        - 'M03: Answers'

        responses:
          '200': 
            description: 'Ok. Show the question relative to answer with it''s answrs included'

    /answer/{answer}/edit:
      get:
        operationId: R303
        summary: 'R303: Get the form to edit a Answer'
        description: 'Turn the answer field into editable areas'
        tags:
          - 'M03: Answers'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '200':
            description: 'Ok. Change the fields into editable'
      put:
        operationId: R304
        summary: 'R304: Edit the answer'
        description: 'Process the new information and update the answer'
        tags:
          - 'M03: Answers'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        requestBody:
          required: True
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  content:
                    type: string
                required:
                  - title
                  - content
        responses:
          '302':
            description: 'Redirect after updating answer information'
            headers:
              Location:
                schema:
                  type: string
                examples: 
                  302Success:
                    description: 'Successfully Updated the answer. Redirect to question page'
                    value: '/question/{question}/show'
                  302Failure:
                    description: 'Failed to edit and update the answer'
                    value: '/question/{question}/show'

    /answer/{answer}/delete:
      delete:
        operationId: R304
        summary: 'R304: Delete an answer'
        description: 'Process the deletion of an answer.'
        tags:
         - 'M03: Answers'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '302':
            description: 'Redirect user after deleting an answer'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successfully deleted. Redirect to question page.'
                    value: '/question/{question}/show'
                  302Failure:
                    description: 'Failed to delete the answer. Redirect to question page'
                    value: '/question/{question}/show'

#-------------------- M04 -----------------------
    /comment/create:
      post:
        operationId: R401
        summary: 'R401: Create comment'
        description: 'Process the comment form and create the comment'
        tags:
          - 'M04: Comments'


        requestBody:
          required: True
          content:
            application/x-www-form-urlencoded:
              schema:
                type: object
                properties:
                  content:
                    type: string
                required:
                  - content
        responses:
          '302':
            description: 'Redirect after creating the new Comment'
            headers:
              Location:
                schema: 
                  type: string
                examples:
                  302Success:
                    description: 'Successfully created a comment. Redirect to question page'
                    value: '/question/{question}/show'
                  302Failure:
                    description: 'Failed to create a comment. Redirect to the question page'
                    value: '/question/{question}/show'


    /comment/{id}/show:
      get:
        operationId: R402
        summary: 'R402: Show comment'
        description: 'Show the question page relative to the question/answer that owns the comment'
        tags:
        - 'M04: Comments'

        responses:
          '200': 
            description: 'Ok. Show the question relative to the question/answer that owns the comment'

    /comment/{id}/delete:
      delete:
        operationId: R403
        summary: 'R403: Delete comment'
        description: 'Process the deletion of a comment.'
        tags:
         - 'M04: Comments'
        parameters:
          - in: path
            name: id
            schema:
              type: integer
            required: True
        responses:
          '302':
            description: 'Redirect user after deleting a comment'
            headers:
              Location:
                schema:
                  type: string
                examples:
                  302Success:
                    description: 'Successfully deleted. Redirect to question page.'
                    value: '/question/{question}/show'
                  302Failure:
                    description: 'Failed to delete the answer. Redirect to question page'
                    value: '/question/{question}/show'

#--------------- M05 --------------------

    /home:
      get:
        operationId: R501
        summary: 'R501: Show the Home page'
        description: 'Show the view for the home page'
        tags:
          - 'M05: User Administration and Static pages'
        responses:
          '200':
            description: 'Ok. Show Home Page'


#--------------- M06 --------------------

    /search:
      get:
        operationId: R601
        summary: 'R601: Show search results'
        description: 'Show the result of the Searche'
        tags:
          - 'M06: Searches'
        responses:
          '200':
            description: 'Ok. Show search results'
~~~



# A8: Vertical prototype

The Vertical Prototype includes the implementation of the high priority features marked as necessary (with an asterisk) in the common and theme requirements documents. This artifact aims to validate the architecture presented, also serving to gain familiarity with the technologies used in the project.

The implementation is based on the LBAW Framework and includes work on all layers of the architecture of the solution to implement: user interface, business logic and data access. The prototype includes the implementation of pages of visualization, insertion, edition and removal of information, the control of permissions in the access to the implemented pages, and the presentation of error and success messages.

## 1. Implemented Features


### 1.1 Implemented User Stories

|     **User Story reference**     |**Name**|**Priority**|**Description**|
|----------|----------|----------|---|
|US01|Sign-in|High| As As a Visitor, I want to authenticate into the system, so that I can access privileged information
|US02|Sign-up|high|As a Visitor, I want to register myself into the system, so that I can afterwards authenticate myself
|US03|View Profile|high| As a Visitor, I want to access my profile, so that I can check my info and questions/answers
|US04|Add Question|high| 	As a User, I want to create a question, so that I can post new questions and get answers for my doubts
|US05|Edit Question|high|	As a Owner, I want to edit a question, so that I can change it if i do a mistake when creating it
|US06|Delete Question|high|	As a Owner, I want to delete a question, so that I can delete it in case of doing a mistake when creating
|US07|Add Answer|high|	As a User, I want to add an answer to a question, so that I can give my opinion and enage in the threads.
|US08|Edit Answer|high|	As a Owner, I want to edit an answer, so that I can change it if i do a mistake when creating it
|US09|Delete Answer|high|	As a Owner, I want to delete an answer, so that I can delete it in case of doing a mistake when creating
|US10|View Top Questions|high|	As a User, I want to see the most trending questions, so that I can keep updated about the trends
|US11|Browse Question|high|	As a User, I want to browse through questions, so that I can find a specific question more easily
|US12|Delete Question|high|	As a Owner, I want to delete a question, so that I can delete it in case of doing a mistake when 
|US13|Home Page|high|	As a Visitor, I want to see the home page, so that I can see a brief presentation of the website

### 1.2 Implemented Web Resources

Module M01: Authentication and Individual Profile

|**Web Resource Reference**|**URL**|
|--------------------------|-------|
|R101 Login Form  | GET /login |
|R102: Login Action | POST /login|
|R103: Logout Action   | POST /logout|
|R104: Signup Form  | GET /register|
|R105: Signup Action | POST /register |
|R106: View User Profile  | GET /user/{id}/profile|
|R107: Edit Profile  | POST /user/{id}/profile|
|R108: View user profile edit form  | GET /users/{id}/profile/edit|



Module M02: Questions

|**Web Resource Reference**|**URL**|
|--------------------------|-------|
|R201: Get the question creation form | GET  /question/create |
|R202: Create Questions | POST  /question |
|R203: Get the question to show | GET /question/{question}/show |
|R204: Get the form to edit a Question| GET /question/{question}/edit|
|R205: Edit the question | PUT  /question/{question}/edit|
|R206: Delete a question | DELETE /question/{question}/delete |
|R207: View Top Questions | GET /top_questions |

Module M03: Answers

|**Web Resource Reference**|**URL**|
|--------------------------|-------|
|R301: Create an answer| POST /question/{question}/answer |
|R302: Show the answer| GET /answer/{answer}/show |
|R303: Get the form to edit an Answer| GET /answer/{answer}/edit |
|R304: Edit the answer| PUT /answer/{answer}/edit |
|R305: Delete an answer| DELETE /answer/{answer}/delete |

Module M04: Comments

|**Web Resource Reference**|**URL**|
|--------------------------|-------|
|R:401 Create comment| POST /comment/create |
|R:402 Show comment| GET /comment/{id}/show|
|R:403 Delete comment| DELETE /comment/{id}/delete|

Module M05: User Administration and Static pages

|**Web Resource Reference**|**URL**|
|--------------------------|-------|
|R501: Show the Home page| GET /home|

Module M06: Searches

|**Web Resource Reference**|**URL**|
|--------------------------|-------|
|R601: Show search results| GET /search|



# 2. Prototype

GitLab link: https://git.fe.up.pt/lbaw/lbaw2324/lbaw23153

Credentials:

admin: admin@example.com/passadmin
regular user: john.doe@example.com/password123

### Revision History

- Fixed inconcistancies in the db schema 
-- Fixed errors in the triggers, transactions and Index 
-- Updated some of the types names to resolve confusion 
~~~~sql
CREATE TYPE vote_type AS ENUM('up', 'down');
CREATE TYPE noti_parent AS ENUM('vote', 'answer');
CREATE TYPE tag_type AS ENUM('CINEMA', 'MUSIC', 'IT', 'MATH'); 
~~~~
-- Added hashing for the passwords 
~~~~sql
INSERT INTO users (id, username, password, email, is_admin, profile_picture_id)
VALUES  
    (1, 'john_doe', '$2a$12$MbowM0hI3CiH7gsCEOM6CuWB4G.1gjzRxOLIGVio.HXRHrThGaeXO', 'john.doe@example.com', false, 'ricardo.jpg'),
    (2, 'jane_smith', '$2a$12$Pbx4EC0OfutMyQRRCg02ge7Qhr/87QgHZ0NHoqTjgQPSvZWc1y6UC', 'jane.smith@example.com', false, 'tiago.jpg'),
    (3, 'admin', '$2a$12$CA10Fbn2QUZZsXdqwuinue60jVOBEFBvbf6VfyX50ZukcdYb0xIPq', 'admin@example.com', true, 'Hasbulla.jpg');
~~~~


|Name  |Email  |Number  |
|--|--|--|
|Guilherme Freire| up202004809@up.pt | 202004809|
|Diogo Vale|up201805152@up.pt| 201805152 |
|João Lopes|up201805078@up.pt| 201805078 |

