#!/bin/bash
printenv | grep -v "no_proxy" >> /etc/environment
supervisord -c /etc/supervisor/conf.d/supervisord.conf