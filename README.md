# Skin Deep server

The entire server for the Skin Deep website.

## Structure

- Trellis server (./trellis)
- Bedrock wordpress installation (./site)
- Sage-based Skin deep theme (./site/web/app/themes/sd-theme)

As established by the above frameworks, dependencies are managed using standard tooling:
- Bedrock and Sage PHP dependencies managed using composer
- Sage node dependencies are managed using yarn

## Updates

### Trellis, Bedrock, and Sage

Updates to [Trellis](https://github.com/roots/trellis), [Bedrock](https://github.com/roots/bedrock) and [Sage](https://github.com/roots/sage) are managed following the process outlined by [hooverlunch](https://discourse.roots.io/t/best-practices-to-update-trellis/5386/32).

Trellis, Bedrock and Sage were all added as remotes, then merged as subtrees with the following commands:
```
git subtree add --prefix=trellis trellis master --squash
git subtree add --prefix=site bedrock master --squash
git subtree add --prefix=site/web/app/themes/sd-theme sage master --squash
```

Updating these components can be achieved with the following commands:
```
git subtree pull --prefix=trellis trellis master --squash
git subtree pull --prefix=site bedrock master --squash
git subtree pull --prefix=site/web/app/themes/sd-theme sage master --squash
```

### trellis-database-uploads-migration

Syncing of the wordpress database and uploads is performed using [trellis-database-uploads-migration](https://github.com/valentinocossar/trellis-database-uploads-migration).

A remote db-uploads-migration was added, and a branch tracking master created:
```
git branch db-uploads-migration/master db-uploads-migration
```

Updates to the remote can be merged in with:
```
git fetch db-uploads-migration
git pull db-uploads-migration master
```