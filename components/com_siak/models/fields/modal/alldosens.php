<?php

defined('_JEXEC') or exit;

class JFormFieldModal_Alldosens extends JFormField
{
    protected function getInput()
    {
        $value = (int) $this->value > 0 ? (int) $this->value : '';
        $modalId = 'Alldosens_'.$this->id;

        // Add the modal field script to the document head.
        JHtml::_('jquery.framework');
        JHtml::_('script', 'system/modal-fields.js', ['version' => 'auto', 'relative' => true]);

        JFactory::getDocument()->addScriptDeclaration('
			function jSelectDosens_'.$this->id."(id, title, catid, object, url, language) {
				window.processModalSelect('Alldosens', '".$this->id."', id, title, catid, object, url, language);
			}
			");

        if ($value) {
            $title = JFactory::getUser($value)->name;
        }

        // display the default greeting or "Select" if no default specified
        $title = empty($title) ? JText::_('COM_SIAK_MENUITEM_SELECT_DOSEN') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $html = '<span class="input-append">';
        $html .= '<input class="input-medium" id="'.$this->id.'_name" type="text" value="'.$title.'" disabled="disabled" size="35" />';

        // html for the Select button
        $html .= '<a'
            .' class="btn btn-primary hasTooltip'.($value ? ' hidden' : '').'"'
            .' id="'.$this->id.'_select"'
            .' data-toggle="modal"'
            .' role="button"'
            .' href="#ModalSelect'.$modalId.'"'
            .' title="'.JHtml::tooltipText('COM_SIAK_MENUITEM_SELECT_BUTTON_TOOLTIP').'">'
            .'<span class="icon-user" aria-hidden="true"></span> '.JText::_('JSELECT')
            .'</a>';

        // html for the Clear button
        $html .= '<a'
            .' class="btn'.($value ? '' : ' hidden').'"'
            .' id="'.$this->id.'_clear"'
            .' href="#"'
            .' onclick="window.processModalParent(\''.$this->id.'\'); return false;">'
            .'<span class="icon-remove" aria-hidden="true"></span>'.JText::_('JCLEAR')
            .'</a>';

        $html .= '</span>';

        // url for the iframe
        $link = 'index.php?option=com_siak&amp;view=alldosens&amp;layout=modal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';
        $urlSelect = $link.'&amp;function=jSelectDosens_'.$this->id;

        // title to go in the modal header
        $modalTitle = JText::_('COM_SIAK_DOSEN_SELECT_MODAL_TITLE');

        // html to set up the modal iframe
        $html .= JHtml::_(
            'bootstrap.renderModal',
            'ModalSelect'.$modalId,
            [
                'title' => $modalTitle,
                'url' => $urlSelect,
                'height' => '400px',
                'width' => '800px',
                'bodyHeight' => '70',
                'modalWidth' => '80',
                'footer' => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">'.JText::_('JLIB_HTML_BEHAVIOR_CLOSE').'</a>',
            ]
        );

        $class = $this->required ? ' class="required modal-value"' : '';

        $html .= '<input type="hidden" id="'.$this->id.'_id" '.$class
            .' data-required="'.(int) $this->required.'" name="'.$this->name
            .'" data-text="'.htmlspecialchars(JText::_('COM_SIAK_MENUITEM_SELECT_DOSEN', true), ENT_COMPAT, 'UTF-8')
            .'" value="'.$value.'" />';

        return $html;
    }

    protected function getLabel()
    {
        return str_replace($this->id, $this->id.'_id', parent::getLabel());
    }
}
