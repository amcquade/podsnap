## [cli](https://docs.lando.dev/cli/)

### [ssh](https://docs.lando.dev/cli/ssh)
#### shell into a service
`lando ssh -s generator`

#### to use root
`lando ssh -s generator --user root`

### [logs](https://docs.lando.dev/cli/logs.html)
#### Get the logs
lando logs

#### Follow the logs and show timestamps
lando logs -t -f

#### Show logs for only the database and generator services
lando logs -s generator -s database