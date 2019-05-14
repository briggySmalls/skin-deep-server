# Trellis

Ansible playbooks for setting up a LEMP stack for the Skin Deep WordPress.

- Local development environment with Vagrant
- High-performance production servers
- Zero-downtime deploys

## What's included

Trellis will configure a server with the following and more:

* Ubuntu 18.04 Bionic LTS
* Nginx (with optional FastCGI micro-caching)
* PHP 7.3
* MariaDB (a drop-in MySQL replacement)
* SSL support (scores an A+ on the [Qualys SSL Labs Test](https://www.ssllabs.com/ssltest/))
* Let's Encrypt for free SSL certificates
* HTTP/2 support (requires SSL)
* Composer
* WP-CLI
* sSMTP (mail delivery)
* MailHog
* Memcached
* Fail2ban and ferm

## Documentation

Full documentation is available at [https://roots.io/trellis/docs/](https://roots.io/trellis/docs/).

## Requirements

Make sure all dependencies have been installed before moving on:

* [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* [Virtualbox](https://www.virtualbox.org/wiki/Downloads) >= 4.3.10
* [Vagrant](https://www.vagrantup.com/downloads.html) >= 2.1.0

**Windows user?** [Read the Windows getting started docs](https://roots.io/getting-started/docs/windows-development-environment-trellis/) for slightly different installation instructions.

## Installation

This installation uses the recommended directory structure for a Trellis project.

The repo configures everything necessary for development and deployment. However secrets are encrypted with Ansible vault.

Thus the only installation is create a `.vault_pass` file that contains the project's password in this directory

NEVER, EVER, COMMIT & PUSH THIS FILE. IF YOU DO, CONSIDER ALL SECRETS COMPROMISED.

## Local development setup

1. Ensure you're in the trellis directory: `cd trellis`
2. Run `vagrant up`

[Read the local development docs](https://roots.io/trellis/docs/local-development-setup/) for more information.

## Remote server provisioning (staging/production)

For remote servers, installing Ansible locally is an additional requirement. See the [docs](https://roots.io/trellis/docs/remote-server-setup/#requirements) for more information.

A base Ubuntu 18.04 (Bionic) server is required for setting up remote servers. OS X users must have [passlib](http://pythonhosted.org/passlib/install.html#installation-instructions) installed.

1. Run `ansible-playbook server.yml -e env=<environment>` to provision the server

[Read the remote server docs](https://roots.io/trellis/docs/remote-server-setup/) for more information.

## Deploying to remote servers

1. Run `./bin/deploy.sh <environment> <site name>`
2. To rollback a deploy, run `ansible-playbook rollback.yml -e "site=<site name> env=<environment>"`

[Read the deploys docs](https://roots.io/trellis/docs/deploys/) for more information.
