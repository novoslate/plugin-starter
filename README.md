# Plugin Starter

WordPress plugin by Novoslate. See `readme.txt` for the plugin description and changelog.

## Releasing an update

```bash
ship --bump 0.2.0   # updates the version everywhere, then commits, tags, and pushes
```

GitHub Actions builds `plugin-starter.zip` and attaches it to the release. Sites with the plugin
see the update in Dashboard > Updates within about 12 hours, or right away via "Check for updates"
on the Plugins screen.

If a new version arrives as a zip in ~/Downloads instead, just run `ship`.
