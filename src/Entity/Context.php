<?php
    namespace Fei\Service\Audit\Entity;

    use Fei\Entity\AbstractEntity;

    /**
     * Class Context
     *
     * @Entity
     * @Table(name="contexts")
     */
    class Context extends AbstractEntity
    {
        /**
         * @Id
         * @GeneratedValue(strategy="AUTO")
         * @Column(type="integer")
         */
        protected $id;

        /**
         * @ManyToOne(targetEntity="AuditEvent", inversedBy="contexts")
         * @JoinColumn(name="auditEvent_id", referencedColumnName="id", onDelete="CASCADE")
         */
        protected $auditEvent;

        /**
         * @Column(type="string", name="`key`")
         */
        protected $key;

        /**
         * @Column(type="text", name="`value`")
         */
        protected $value;


        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         *
         * @return Context
         */
        public function setId($id)
        {
            $this->id = $id;

            return $this;
        }


        /**
         * @return mixed
         */
        public function getAuditEvent()
        {
            return $this->auditEvent;
        }

        /**
         * @param mixed $auditEvent
         *
         * @return Context
         */
        public function setAuditEvent($auditEvent)
        {
            $this->auditEvent = $auditEvent;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getKey()
        {
            return $this->key;
        }

        /**
         * @param mixed $key
         *
         * @return Context
         */
        public function setKey($key)
        {
            $this->key = $key;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getValue()
        {
            return $this->value;
        }

        /**
         * @param mixed $value
         *
         * @return Context
         */
        public function setValue($value)
        {
            $this->value = $value;

            return $this;
        }
    }
