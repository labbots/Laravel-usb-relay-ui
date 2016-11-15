
## Laravel USB Relay UI ##
----------
This project provides a web interface to interact with the USB controlled relay switch.
The relay driver can be found at https://github.com/labbots/SainsmartUsbRelay.

This application was designed and developed with following requirements in mind.

 - simple, intuitive usage and interface.
 - Simple access management system to prevent unauthorized access of the configured relay.
 - Easy to use APIs to remotely trigger the relays.
 - Task Scheduler to run defined tasks on regular intervals.

Installation
============
This software is based on Laravel 5.2 and requires composer to update dependencies and a database to store user credentials and logging information.

**Dependencies**

 - sainsmart usb relay program
 - git
 - composer
 - php >= 5.6
 - mysql
 - supervisord

**Steps**
  
 - Clone the git repository
 
         git clone https://github.com/labbots/Laravel-usb-relay-ui.git

 - Install the dependencies using composer.

        cd path/to/repo/Laravel-usb-relay-ui/webapp
        composer update

 - Update the file permissions. Storage and bootstrap folder requires read write permission.
 
         chown -R {user}:www-data webapp
         chmod -R 775 storage
         chmod -R 775 bootstrap
         
 - Configure the database. The configs are under config/database.php or can use .env file to configure the database connection.
 -  Configure mail settings. Default is mandrill email service.
 - Create all the database tables using the following command
 
         php artisan migrate --database[=DATABASE]
         
 - Import initial seed data for the database.
 
         php artisan db:seed --database[=DATABASE]
 
 - After the database is setup make sure the required apache settings are configured to access the site. 

          <VirtualHost *:80>
		        DocumentRoot /path/to/sites/Laravel-usb-relay-ui/webapp/public
		        ServerName relay-ui.dev
		        <Directory /path/to/sites/Laravel-usb-relay-ui/webapp/public>
	                Options Indexes FollowSymLinks MultiViews
	                AllowOverride All
	                allow from all
		        </Directory>
		        ErrorLog ${APACHE_LOG_DIR}/relay-ui-error.log
		        LogLevel warn
		        CustomLog ${APACHE_LOG_DIR}/relay-ui-access.log combined
          </VirtualHost>
     
 - Since the driver communicates with the USB relay hardware it requires root access. So add the following line to visudo.

            www-data        ALL=(ALL) NOPASSWD: /usr/local/bin/sainsmartrelay
  
 - The application runs scheduler to execute configured jobs. So following line should be added to the cron tab.
 
          * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
 
 - The application also requires a queue system to perform asynchronous event handling. By default the application uses mysql database for queuing and the event listener is configured as background process using supervisord.

        cd /etc/supervisor/conf.d
        touch relay.conf
        vim relay.conf
        
 and add the following lines to the file.
         
        [program:relay_queue]
        command=php artisan queue:listen --tries=2 
        directory=/path/to/site/
        autostart=true
        autorestart=true
        stderr_logfile=/var/log/relay_queue.err.log
        stdout_logfile=/var/log/relay_queue.out.log

Configuration
============

 - The relay specific configuration is available under config/relay.php. Here you could configure the number of channels in the relay and name of each relay channels for display purposes.

Usage
============
This application provides 3 different ways to interact with the relay.

 - **Web interface**
  The application provides a web interface which the user can log into to control the relay switches using a intuitive dashboard. The web application also allows you to administer user login information. The default admin credentials are loaded using database seeder and can be found in database/seeds/ UsersTableSeeder.php.

 - **Command line**
  The application provides artisan command to set the relay states. The following command can be used to turn on and turn off relay

		php artisan relay:set [options]
	  
 > **Options**:
		  -o, --on[=ON]         Relay number to turn on. This can be comma seperated values.
		  -f, --off[=OFF]       Relay number to turn off. This can be comma seperated values.
		  -d, --delay[=DELAY]   Set delay in seconds for automatic turn off of switched on relay.
		  -q, --quiet           Do not output any message
		  --ansi            Force ANSI output

 - **HTTP Rest API**
   The API provides 2 endpoints to access the relay state and to set relay state.
   
     - To utilize the API, you need a valid API token. This can be generated using the following command
		    
		    php artisan api-key:generate --user-id=1
		    
     The generated API token must be passed in 'X-AUTHORIZATION' header to authenticate the API request.
   The two endpoints that are available are
   
       - api/1.0/relay_status [GET]
       - api/1.0/relay?on={relay_numbers}&off={relay_numbers}&delay={delay_in_seconds} [POST]

     Relay number can be a single relay number or comma separated list of relay numbers.Delay can be set for a on state. So that the relay will be automatically turned off after the set delay time.
