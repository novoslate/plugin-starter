# Novoslate plugin starter

Template for new WordPress plugins with GitHub release updates built in.

## Start a new plugin

1. On GitHub, open `novoslate/plugin-starter` and click **Use this template > Create a new repository**.
   Name the repo after the plugin slug, for example `review-widget` (lowercase, dashes).
2. Clone it into Projects. The folder name must match the repo name:
   ```bash
   git clone git@github-novoslate:novoslate/review-widget.git ~/Projects/review-widget
   ```
3. Run the one-time setup with the plugin's display name:
   ```bash
   cd ~/Projects/review-widget && ./init.sh "Review Widget"
   ```
   It renames every file, class, function, constant, and the update repo URL, then commits.
4. Build the plugin, then ship the first release:
   ```bash
   ship --bump 0.1.0
   ```

## What's included

- Main plugin file with headers, constants, and Plugin Update Checker pointed at the repo.
- A main class with a settings page and a shortcode as working examples.
- `.github/workflows/release.yml`: builds `<repo-name>.zip` on every `v*` tag.
- `.distignore`: keeps dev files out of the release zip.
