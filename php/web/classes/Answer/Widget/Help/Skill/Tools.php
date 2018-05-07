<?php

namespace Answer\Widget\Help\Skill;

class Tools extends \Answer\Widget\Misc\Section {

    public function __construct(\Model\Game\Skill\Skill $s, $classes = "") {
        $res = "";
        $table = new \Quantyl\XML\Html\Table();
        $table->addHeaders(array(
            \I18n::TOOL(),
            \I18n::MUNITION(),
            \I18n::TIME_BONUS(),
            \I18n::AMOUNT_BONUS()
        ));

        foreach (\Model\Game\Skill\Tool::GetFromSkill($s) as $tool) {
            $hasmunitions = false;
            foreach (\Model\Game\Ressource\Munition::GetByWeapon($tool) as $munition) {
                $hasmunitions = true;
                $table->addRow(array(
                    new \Quantyl\XML\Html\A("/Help/Item?id={$tool->item->id}", $tool->item->getImage("icone-inline") . " " . $tool->item->getName()),
                    new \Quantyl\XML\Html\A("/Help/Item?id={$munition->item->id}", $munition->item->getImage("icone-inline") . " " . $munition->item->getName()),
                    round(100 * ($tool->getCoef() - 1.0), 2) . " %",
                    round(100 * $munition->getCoef(), 2) . " %"
                ));
            }
            if (!$hasmunitions) {
                $table->addRow(array(
                    new \Quantyl\XML\Html\A("/Help/Item?id={$tool->item->id}", $tool->item->getImage("icone-med") . " " . $tool->item->getName()),
                    \I18n::NONE(),
                    round(100 * ($tool->getCoef() - 1.0), 2) . " %",
                    "0 %"
                ));
            }
        }

        if ($s->by_hand > 0) {
            $table->addRow(array(
                \I18n::BY_HAND(),
                \I18n::NONE(),
                round(100 * ($s->by_hand - 1.0), 2) . " %",
                "0 %"
            ));
        }

        if ($table->getRowsCount() > 0) {
            $res .= \I18n::HELP_ITEM_TOOL_MESSAGE();
            $res .= $table;
            
        } else {
            $res = "";
        }

        parent::__construct(\I18n::HELP_ITEM_TOOL_TITLE(), "", "", $res, $classes);
    }

}
