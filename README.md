# phpunit-coverage-badge

Work in progress. To use as a Github actions CI workflow step, after the coverage test pass
is complete. Expects clover.xml formatted coverage.

# Inputs

Paths are always relative to the root of your repository.

### `coverage_badge_path`
The path inside the repository where the created badge should be saved. Can be anywhere except inside the .github folder.

**default: badge.svg**

## Badge commit and push

### `report`
Path to the clover.xml file generate by phpunit.

### `repo_token`
Token to push the badge into the repository. Just add "${{ secrets.GITHUB_TOKEN }}" as the input.

**default: NOT_SUPPLIED**

### `commit_message`
Commit message that will be used to commit the updated badge and clover file.

**default: Update code coverage badge**

### `commit_email`
Email that will be used for the commit.

**default: 41898282+github-actions[bot]@users.noreply.github.com**

This will display all commits as added by the official github actions account/page.

### `commit_name`
Name that will be used for the commit.

**default: Github Actions Bot**

