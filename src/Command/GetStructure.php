<?php namespace Anomaly\LocalDocumentationExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DocumentationModule\Project\Contract\ProjectInterface;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Local\Client;

/**
 * Class GetStructure
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\LocalDocumentationExtension\Command
 */
class GetStructure implements SelfHandling
{

    use DispatchesJobs;

    /**
     * The project instance.
     *
     * @var ProjectInterface
     */
    protected $project;

    /**
     * The project reference.
     *
     * @var string
     */
    protected $reference;

    /**
     * Create a new GetStructure instance.
     *
     * @param ProjectInterface $project
     * @param string           $reference
     */
    public function __construct(ProjectInterface $project, $reference)
    {
        $this->project   = $project;
        $this->reference = $reference;
    }

    /**
     * Handle the command.
     *
     * @param ConfigurationRepositoryInterface $configuration
     * @param AddonCollection                  $addons
     * @return \stdClass
     */
    public function handle(ConfigurationRepositoryInterface $configuration, AddonCollection $addons)
    {
        $namespace = 'anomaly.extension.local_documentation';

        /* @var Addon $addon */
        if ($addon = $addons->get($key = $configuration->value($namespace . '::addon', $this->project->getSlug()))) {
            $path = $addon->getPath('docs/structure.json');
        }

        if (!isset($path)) {
            $path = base_path($key . '/docs/structure.json');
        }

        return json_decode(file_get_contents($path), true);
    }
}
