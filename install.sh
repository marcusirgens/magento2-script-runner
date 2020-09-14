#!/bin/bash
# Simple installer for the script runner

# https://www.davidpashley.com/articles/writing-robust-shell-scripts/
set -eu

# Clone and remove git directory
# https://stackoverflow.com/a/11498124/9738360
git clone --depth=1 --branch=main git@github.com:marcusirgens/magento2-script-runner.git ./script_runner
rm -rf ./script_runner/.git

# Get the global gitignore
global_ignore="$(git config --global --get core.excludesfile)"

# If that file does not exist, don't do anything.
if [ -n "$global_ignore" ]; then
    if ! grep -Eq '^script_runner.*' "$global_ignore"; then
        # https://stackoverflow.com/questions/1885525/how-do-i-prompt-a-user-for-confirmation-in-bash-script
        read -p "Do you want to add script_runner/* to your global .gitignore file? [y/n]" -n 1 -r
        echo    # (optional) move to a new line
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            echo "script_runner/*" >> "$global_ignore"
        fi
    fi
fi
