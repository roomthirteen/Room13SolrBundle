Room13SolrBundle
================

A symfony2 bundle using the php solor api to speak with a solr server.

NOTE: work in progress, this is gonna be great but almost no docs so far


Usage
===============

Define a custom index

    <?php
    
    namespace Room13\GeoBundle\Solr;
    
    use Room13\SolrBundle\Solr\DoctrineSolrIndex;
    use Doctrine\ORM\EntityManager;
    
    class CityIndex extends DoctrineSolrIndex
    {
    
        public function getName()
        {
            return 'room13_geo_city';
        }
    
    
        public function getFields()
        {
            return array(
                'name'  => 's',
                'lat'   => 'f',
                'lng'   => 'f',
            );
        }
    
        public function getType()
        {
            return 'Room13\GeoBundle\Entity\City';
        }    
    
    }


2: Register index

    <service id="room13.geo.solr.index.city" class="Room13\GeoBundle\Solr\CityIndex">
      <tag name="room13.solr.index" />
      <argument type="service" id="doctrine.orm.entity_manager" />
    </service>


Build index
    ./app/console room13:solr:index --all


Search index
    ./app/console room13:solr:search --index=room13_geo_city Sup* 
