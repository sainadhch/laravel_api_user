# laravel_api_user
user Authentication Using Laravel 5.6 API

# API LIST
  # 1. api/users/register
        - Header data 
            - Accept: application/json
            - Content-Type: application/x-www-form-urlencoded
        - Body data
            - name: <name_value>
            - username: <username_value>
            - password: <password_value>
            - phone: <phone_value>

  # 2. api/user/login
        - Header data 
            - Accept: application/json
            - Content-Type: application/x-www-form-urlencoded
        - Body data
            - username: <username_value>
            - password: <password_value>

  # 3. api/user/index
        - Header data 
            - Accept: application/json
            - Content-Type: application/x-www-form-urlencoded
        - Body data
            - token: <token_value>

  # 4. api/user/logout_other_sessions
        - Header data 
            - Accept: application/json
            - Content-Type: application/x-www-form-urlencoded
        - Body data
            - token: <token_value>
            
# Token Management Details.
   # Tokens expire after 1 hour.
   # User able to invalidate his/her existing logged-in sessions(from other devices Using 'api/user/logout_other_sessions' API).
   # displays a list of users and their latest token (Using 'api/user/index' API).
            

