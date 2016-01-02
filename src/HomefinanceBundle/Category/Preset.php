<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

namespace HomefinanceBundle\Category;

use HomefinanceBundle\Entity\Administration;
use HomefinanceBundle\Entity\Category;

class Preset {

    public function createPreset(Administration $administration) {
        $builder = new Builder();
        $root = $builder->createRootCategory($administration);

        $inkomsten = $builder->addChild($root, 'Inkomsten', Type::INCOME);
        $builder->addChild($inkomsten, 'Werk', Type::INCOME);
        $builder->addChild($inkomsten, 'Overige inkomsten', Type::INCOME);

        $huishoudelijke_uitgaven = $builder->addChild($root, 'Huishoudelijke uitgaven', Type::EXPENSE);
        $builder->addChild($huishoudelijke_uitgaven, 'Boodschappen', Type::EXPENSE);
        $builder->addChild($huishoudelijke_uitgaven, 'Persoonlijke verzorging', Type::EXPENSE);
        $builder->addChild($huishoudelijke_uitgaven, 'Huishoudelijke artikelen', Type::EXPENSE);


        $builder->addChild($root, 'Kleding', Type::EXPENSE);

        $woon_lasten = $builder->addChild($root, 'Huis en inrichting', Type::EXPENSE);
        $builder->addChild($woon_lasten, 'Huur / Hypotheek', Type::EXPENSE);
        $builder->addChild($woon_lasten, 'Gas, water en licht', Type::EXPENSE);
        $builder->addChild($woon_lasten, 'Inrichting', Type::EXPENSE);
        $builder->addChild($woon_lasten, 'Meubels', Type::EXPENSE);
        $builder->addChild($woon_lasten, 'Telefoon, internet en tv', Type::EXPENSE);
        $builder->addChild($woon_lasten, 'Onderhoud', Type::EXPENSE);

        $abo = $builder->addChild($root, 'Abonnementen en contributies', Type::EXPENSE);
        $builder->addChild($abo, 'Kranten en tijdschriften', Type::EXPENSE);
        $builder->addChild($abo, 'Bibliotheek', Type::EXPENSE);
        $builder->addChild($abo, 'Sport', Type::EXPENSE);

        $autokosten = $builder->addChild($root, 'Autokosten', Type::EXPENSE);
        $builder->addChild($autokosten, 'Brandstof', Type::EXPENSE);
        $builder->addChild($autokosten, 'Parkeerkosten', Type::EXPENSE);
        $builder->addChild($autokosten, 'Onderhoud', Type::EXPENSE);
        $builder->addChild($autokosten, 'Wegenbelasting', Type::EXPENSE);
        $builder->addChild($autokosten, 'Verzekering', Type::EXPENSE);
        $builder->addChild($autokosten, 'Aanschaf', Type::EXPENSE);

        $vervoer = $builder->addChild($root, 'Vervoer', Type::EXPENSE);
        $builder->addChild($vervoer, 'Fiets(onderhoud)', Type::EXPENSE);
        $builder->addChild($vervoer, 'OV Abonnement', Type::EXPENSE);
        $builder->addChild($vervoer, 'OV Chipkaart', Type::EXPENSE);

        $verzekeringen = $builder->addChild($root, 'Verzekeringen', Type::EXPENSE);
        $builder->addChild($verzekeringen, 'Inboedel en WA', Type::EXPENSE);
        $builder->addChild($verzekeringen, 'Zorgverzekering', Type::EXPENSE);
        $builder->addChild($verzekeringen, 'Uitvaartverzekering', Type::EXPENSE);
        $builder->addChild($verzekeringen, 'Reisverzekering', Type::EXPENSE);

        $zorgkosten = $builder->addChild($root, 'Zorgkosten', Type::EXPENSE);

        $hobby = $builder->addChild($root, 'Hobby', Type::EXPENSE);
        $builder->addChild($hobby, 'Boeken', Type::EXPENSE);
        $builder->addChild($hobby, 'Films en muziek', Type::EXPENSE);
        $builder->addChild($hobby, 'Muziek', Type::EXPENSE);

        $uitgaan = $builder->addChild($root, 'Vakanties & Dagjes weg', Type::EXPENSE);
        $builder->addChild($uitgaan, 'Uiteten', Type::EXPENSE);
        $builder->addChild($uitgaan, 'Dagjes weg', Type::EXPENSE);
        $builder->addChild($uitgaan, 'Vakantie', Type::EXPENSE);

        $builder->addChild($root, 'Overboekingen van/naar eigenrekeningen', Type::SUSPENSE);

        return $root;
    }

}