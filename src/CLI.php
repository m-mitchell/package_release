<?php

/* vim: set noexpandtab tabstop=4 shiftwidth=4 foldmethod=marker: */

namespace silverorange\ModuleRelease;

use Psr\Log;

/**
 * @package   ModuleRelease
 * @author    Michael Gauthier <mike@silverorange.com>
 * @copyright 2016 silverorange
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @license   http://www.opensource.org/licenses/mit-license.html MIT License
 */
class CLI implements Log\LoggerAwareInterface
{
	const VERBOSITY_NONE     = 0;
	const VERBOSITY_MESSAGES = 1;
	const VERBOSITY_DETAILS  = 2;

	/**
	 * @var \Console_CommandLine
	 */
	protected $parser = null;

	/**
	 * @var \silverorange\ModuleRelease\Manager
	 */
	protected $manager = null;

	/**
	 * The logging interface of this application.
	 *
	 * @var \Psr\Log\LoggingInterface
	 */
	protected $logger = null;

	/**
	 * @var integer
	 */
	protected $verbosity = self::VERBOSITY_NONE;

	public function __construct(\Console_CommandLine $parser,
		Manager $manager, Log\LoggerInterface $logger)
	{
		$this->setParser($parser);
		$this->setManager($manager);
		$this->setLogger($logger);
	}

	public function setParser(\Console_CommandLine $parser)
	{
		$this->parser = $parser;
	}

	public function setManager(Manager $manager)
	{
		$this->manager = $manager;
	}

	public function setLogger(Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function run()
	{
		if (!$this->manager->isInGitRepo()) {
			$this->logger->error(
				'This tool must be run from a git repository.' . PHP_EOL
			);
			exit(1);
		}

		if (!$this->manager->isComposerModule()) {
			$this->logger->error(
				'Could not find “composer.json”. Make sure you are in the '.
				'project root and the project is a composer module.' . PHP_EOL
			);
			exit(1);
		}

		$repo_name = $this->manager->getRepoName();

		try {
			$result = $this->parser->parse();

// 3. get new release version from remote
// 4. create release branch `release-foo` from remote branch
// 5. tag branch
// 6. push tag
// 7. remove release branch

		} catch (\Console_CommandLine_Exception $e) {
			$this->logger->error($e->getMessage() . PHP_EOL);
			exit(1);
		} catch (\Exception $e) {
			$this->logger->error($e->getMessage() . PHP_EOL);
			$this->logger->error($e->getTraceAsString() . PHP_EOL);
			exit(1);
		}


/*
if ($so_remote === null) {
	echo "No remote set up for silverorange.\n";
	exit(1);
}

// get the current branch name. By convention, this should match on
// silverorange.
$branch = trim(`git rev-parse --abbrev-ref HEAD`);


// create a fresh branch from silverorange to do the packaging
$result = `git checkout -b package-$branch $so_remote/$branch 2>&1`;
if (preg_match('/^error/', $result) === 1) {
	echo $result;
	echo "Failed to checkout new branch for package release.\n";
	exit(1);
}

	// push changes to silverorange/$branch
	echo `git push  $so_remote package-$branch:$branch`;

	// tag release
	$tag_name = $package_version;
	echo `git tag -a $tag_name -m "Release $tag_name"`;
	echo `git push $so_remote $tag_name`;

}

`git checkout $branch`;
`git branch -D package-$branch`;

`git fetch $so_remote`;
`git rebase $so_remote/$branch`;
`git push origin $branch`;
*/
	}
}