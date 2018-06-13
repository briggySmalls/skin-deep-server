# Skin Deep server

The entire server for the Skin Deep website.

## Structure

- Trellis server (./trellis)
- Bedrock wordpress installation (./site)
- Sage-based Skin deep theme (./site/web/app/themes/sd-theme)

## Updates

Updates to Trellis, Bedrock and Sage are managed following the process outlined by [hooverlunch](https://discourse.roots.io/t/best-practices-to-update-trellis/5386/32).

Trellis, Bedrock and Sage were all created as subtrees with a command like the following:
```
git subtree add --prefix=trellis trellis master --squash
```

As a result, new updates to trellis may reviewed with:
```
git fetch trellis master
git diff trellis/master HEAD:trellis --name-status
```

And brought in with:
```
git subtree pull --prefix=trellis trellis master --squash
```

