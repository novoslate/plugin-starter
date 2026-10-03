#!/bin/bash
# One-time setup for a plugin created from the Novoslate plugin starter.
# Run inside the new plugin's folder: ./init.sh "Review Widget"
# The folder name (for example review-widget) becomes the plugin slug.
set -euo pipefail
cd "$(dirname "$0")"

SLUG=$(basename "$PWD")
if [ "$SLUG" = "plugin-starter" ]; then
	echo "This is the template itself. Clone it into a folder named after your plugin first."
	exit 1
fi
if ! echo "$SLUG" | grep -Eq '^[a-z][a-z0-9]*(-[a-z0-9]+)*$'; then
	echo "Folder name '$SLUG' must be lowercase letters, numbers, and dashes (for example review-widget)."
	exit 1
fi

# Display name: argument, or the slug in title case.
NAME="${1:-$(echo "$SLUG" | perl -pe 's/(^|-)(\w)/($1 ? " " : "") . uc($2)/ge')}"
SNAKE=$(echo "$SLUG" | tr '-' '_')                                        # review_widget
CONST=$(echo "$SNAKE" | tr '[:lower:]' '[:upper:]')                       # REVIEW_WIDGET
CLASS=$(echo "$SLUG" | perl -pe 's/(^|-)(\w)/($1 ? "_" : "") . uc($2)/ge') # Review_Widget

# GitHub owner/repo from the clone URL, falling back to the Novoslate org.
REMOTE=$(git remote get-url origin 2>/dev/null || true)
REPO=$(echo "$REMOTE" | perl -ne 'print $1 if m{github[^:/]*[:/]+([^/]+/[^/]+?)(?:\.git)?$}')
REPO="${REPO:-novoslate/$SLUG}"

echo "Setting up:"
echo "  Name:   $NAME"
echo "  Slug:   $SLUG"
echo "  Class:  $CLASS"
echo "  Prefix: ${SNAKE}_ / ${CONST}_"
echo "  Repo:   github.com/$REPO"
read -r -p "Continue? [y/N] " ok
[ "$ok" = "y" ] || [ "$ok" = "Y" ] || { echo "Stopped."; exit 0; }

export NAME SLUG SNAKE CONST CLASS REPO
find . -type f \
	-not -path './.git/*' -not -path './lib/*' \
	-not -name 'init.sh' -not -name '*.png' -not -name '*.jpg' \
	-print0 | while IFS= read -r -d '' f; do
	perl -pi -e '
		s{novoslate/plugin-starter}{$ENV{REPO}}g;
		s/Plugin_Starter/$ENV{CLASS}/g;
		s/PLUGIN_STARTER/$ENV{CONST}/g;
		s/plugin_starter/$ENV{SNAKE}/g;
		s/plugin-starter/$ENV{SLUG}/g;
		s/Plugin Starter/$ENV{NAME}/g;
	' "$f"
done

# Rename files that carry the template slug.
find . -depth -name '*plugin-starter*' -not -path './.git/*' -not -path './lib/*' | while read -r p; do
	mv "$p" "$(dirname "$p")/$(basename "$p" | sed "s/plugin-starter/$SLUG/")"
done

rm -f TEMPLATE.md init.sh

if git rev-parse --git-dir >/dev/null 2>&1; then
	git add -A
	git commit -qm "Initialize $NAME from plugin starter"
	echo "Done. Committed. Build your plugin, then ship the first release with: ship --bump 0.1.0"
else
	echo "Done. Build your plugin, then ship the first release with: ship --bump 0.1.0"
fi
