server 'coderdojo.nl',
    user: 'coderdojo',
    roles: %w{web app db},
    ssh_options: {
        forward_agent: false,
        auth_methods: ["publickey"],
        keys: ["config/coderdojo_deploy"]
    }


set :pty, true

set :deploy_to, '/home/coderdojo/coderdojo.nl'
