## Test application

#### Table of contests
* [Requirements](#requirements)
* [Config](#config)
* [Preparation](#preparation)
* [Start dev server](#start-dev-server)

##### Requirements
* PHP > 7.1

##### Config
Application read environment variables. All configuration properties described in `.env.example`
For dev environment you also can create `.env` file in root folder

##### Preparation
* Run all sql scripts in `database` folder
* Optionally set `BASE_STORAGE_DIR` environment variable. Application will save there all images, logs, cache etc.
* Create symlinc to images folder:
`BASE_STORAGE_DIR/public/images` to `application_root/public/images`

Alternatively on *nix system with bash shell run `prepare.sh` script from root folder with environment parameters:
`DB_NAME=test DB_PASSWORD=test DB_USER=test sh prepare.sh `

##### Start dev server
* Create `.env` file or set env variables
* `php -S localhost:8000 -t public` starts PHP build-in server on port 8000.


