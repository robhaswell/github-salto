## Dependency tagging
Within your issues (and comments) write something along the lines of:
> Depends on #23

This way `github-salto` will know that this issue depends on/is blocked by issue 23.
You can also configure your own regexp matching pattern in `resources/config/default.php`.

## Github OAuth token
To authenticate against the Github-API run `graph auth` once manually.
The script will ask for your credentials and request an OAuth-token with Github. Your username/password will not be stored.
```sh
./scripts/cm.php graph auth
```

## Dot output
Run the `graph create`-command to traverse through the issues of a given repository,
and create a dependency graph which will be printed in the [dot](http://www.graphviz.org/content/dot-language)-format to the standard output.
```sh
./scripts/cm.php graph create <user-name> <repository-name>
```

You can then pass this dot-script to the `dot` program to render e.g. to PDF:
```sh
./scripts/cm.php graph create <user-name> <repository-name> | dot -T pdf > output.pdf
```
