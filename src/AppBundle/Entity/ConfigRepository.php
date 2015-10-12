<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 12.10.15
 * Time: 19:13
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConfigRepository extends EntityRepository
{
    protected $configuration;

    protected function loadConfiguration()
    {
        $configuration = $this->findAll();
        foreach ($configuration as $parameter) {
            /** @var Config $parameter */
            $this->configuration[$parameter->getMask()] = $parameter->getContent();
        }
    }

    public function get($mask)
    {
        if ($this->configuration === null) {
            $this->loadConfiguration();
        }

        return isset($this->configuration[$mask]) ? $this->configuration[$mask] : null;
    }
}