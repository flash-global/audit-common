<?php
namespace Fei\Service\Audit\Entity;

use Fei\Entities\ContextAwareEntityInterface;
use Fei\Entities\ContextAwareTrait;
use ObjectivePHP\Gateway\Entity\Entity;
use ObjectivePHP\Primitives\String\Snake;

/**
 * Class AuditEvent
 *
 * @package Fei\Service\Audit\Entity
 */
class AuditEvent extends Entity implements ContextAwareEntityInterface
{
    use ContextAwareTrait;

    // category
    const SECURITY    = 1;
    const PERFORMANCE = 2;
    const BUSINESS    = 4;
    const AUDIT       = 8;
    const SQL         = 16;
    const TECHNICAL   = 32;
    const TRACKING    = 64;

    // level
    const LVL_DEBUG   = 1;
    const LVL_INFO    = 2;
    const LVL_WARNING = 4;
    const LVL_ERROR   = 8;
    const LVL_PANIC   = 16;

    protected static $levelLabels = [
        self::LVL_DEBUG => 'Debug',
        self::LVL_INFO => 'Info',
        self::LVL_WARNING => 'Warning',
        self::LVL_ERROR => 'Error',
        self::LVL_PANIC => 'Panic'
    ];

    protected static $categoryLabels = [
        self::SECURITY    => 'Security',
        self::PERFORMANCE => 'Performance',
        self::BUSINESS    => 'Business',
        self::AUDIT       => 'Audit',
        self::SQL         => 'SQL',
        self::TECHNICAL   => 'Technical',
        self::TRACKING    => 'Tracking'
    ];

    protected $entityCollection = "audit_events";

    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $reportedAt;

    /**
     * @var int
     */
    protected $level = 2;

    /**
     * @var int
     */
    //protected $flags = 0;

    /**
     * @var string
     */
    protected $namespace = '/';

    /**
     * @var string
     */
    protected $message;

    /**
     * @var json
     */
    protected $backTrace;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var string
     *
     * This represents URL+QUERY STRING for HTTP environment and command line for CLI
     */
    protected $command;

    /**
     * @var string
     */
    protected $origin;

    /**
     * @var int
     */
    protected $category;

    /**
     * Environment of the originating application
     *
     * @var string
     */
    protected $env = 'n/c';


    /**
     * Audit constructor.
     */
    public function __construct($data = null)
    {
        //parent::__construct($data);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return AuditEvent
     */
    public function setId(int $id) : AuditEvent
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReportedAt()
    {
        return $this->reportedAt;
    }

    /**
     * @param $reportedAt
     *
     * @return AuditEvent
     */
    public function setReportedAt($reportedAt) : AuditEvent
    {
        if (is_string($reportedAt)) {
            $reportedAt = new \DateTime($reportedAt);
        }

        $this->reportedAt = $reportedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return AuditEvent
     */
    public function setLevel($level) : AuditEvent
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return string
     */
    public function getLevelLabel()
    {
        $labels = [];
        foreach (static::$levelLabels as $level => $label) {
            if ($level & $this->level) {
                $labels[] = $label;
            }
        }

        return implode(', ', $labels);
    }

    /**
     * @return array
     */
    public static function getLevelLabels() : array
    {
        return static::$levelLabels;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     *
     * @return AuditEvent
     */
    public function setNamespace($namespace) : AuditEvent
    {
        $parts = explode('/', $namespace);

        foreach ($parts as &$part) {
            $part = Snake::case($part, '-');
        }

        $namespace = implode('/', $parts);
        $namespace = '/' . trim($namespace, '/');
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @return mixed
     */
    /*public function getFlags()
    {
        return $this->flags;
    }*/

    /**
     * @param int $flags
     * @return $this
     */
    /*public function setFlags($flags)
    {
        $this->flags = $flags;
        return $this;
    }*/

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return AuditEvent
     */
    public function setMessage($message) : AuditEvent
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return json
     */
    public function getBackTrace()
    {
        $backTrace = $this->backTrace;

        return $backTrace;
    }

    /**
     * @param $backTrace
     *
     * @return AuditEvent
     */
    public function setBackTrace($backTrace) : AuditEvent
    {
        $this->backTrace = $backTrace;

        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return AuditEvent
     */
    public function setUser($user) : AuditEvent
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param string $server
     *
     * @return AuditEvent
     */
    public function setServer($server) : AuditEvent
    {
        $this->server = $server;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     *
     * @return AuditEvent
     */
    public function setCommand($command) : AuditEvent
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Return the appropriate label to describe the command depending on log origin
     */
    public function getCommandLabel() : string
    {
        return $this->origin == 'http' ? 'url' : 'command line';
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     *
     * @return AuditEvent
     */
    public function setOrigin($origin) : AuditEvent
    {
        if (!in_array($origin, ['http', 'cron', 'cli'])) {
            throw new \InvalidArgumentException('NotificationEndpoint origin has to be either "http", "cron" or "cli"');
        }
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param int $category
     *
     * @return AuditEvent
     */
    public function setCategory($category) : AuditEvent
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryLabel() : string
    {
        $labels = [];
        foreach (static::$categoryLabels as $category => $label) {
            if ($category & $this->category) {
                $labels[] = $label;
            }
        }

        return implode(', ', $labels);
    }

    /**
     * @return array
     */
    public static function getCategoryLabels() : array
    {
        return static::$categoryLabels;
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param string $env
     *
     * @return AuditEvent
     */
    public function setEnv($env) : AuditEvent
    {
        $this->env = strtolower($env);

        return $this;
    }

    /**
     * @return String
     */
    public function getEntityCollection(): String
    {
        return $this->entityCollection;
    }

    /**
     * @return array
     */
    public function getEntityFields(): array
    {
        if ($this->getArrayCopy()) {
            return array_keys($this->getArrayCopy());
        } else {
            $fields = [];

            foreach (get_class_methods($this) as $method) {
                if (in_array(
                    $method,
                    [
                        'getEntityFields',
                        'getEntityIdentifier',
                        'getEntityCollection',
                        'getArrayCopy',
                        'getFlags',
                        'getIterator',
                        'getIteratorClass',
                        'getLevelLabel',
                        'getLevelLabels',
                        'getCommandLabel',
                        'getCategoryLabel',
                        'getCategoryLabels'
                    ]
                )) {
                    continue;
                }

                if (strpos($method, 'get') === 0) {
                    $fields[] = Snake::case(substr($method, 3));
                }
            }

            return $fields;
        }
    }
}
