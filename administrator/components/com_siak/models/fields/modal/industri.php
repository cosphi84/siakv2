<?php

defined('_JEXEC') or exit;

class JFormFieldModal_Industri extends JFormField
{
    protected function getInput()
    {
        $value = (int) $this->value > 0 ? (int) $this->value : '';
        $modalId = 'Industri_'.$this->id;

        // Add the modal field script to the document head.
        JHtml::_('jquery.framework');
        JHtml::_('script', 'system/modal-fields.js', ['version' => 'auto', 'relative' => true]);

        JFactory::getDocument()->addScriptDeclaration('
			function jSelectIndustri_'.$this->id."(id, title, catid, object, url, language) {
				window.processModalSelect('Industri', '".$this->id."', id, title, catid, object, url, language);
			}
			");

        if ($value) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName(['nama']))
                ->from($db->quoteName('#__siak_industri'))
                ->where($db->quoteName('id').' = '.(int) $value)
            ;
            $db->setQuery($query);

            try {
                $result = $db->loadResult();
            } catch (RuntimeException $e) {
                throw new Exception($e->getMessage(), 500);
            }
        }

        // display the default greeting or "Select" if no default specified
        $title = empty($result) ? JText::_('COM_SIAK_MENUITEM_SELECT_INDUSTRI') : htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        $html = '<span class="input-append">';
        $html .= '<input class="input-medium" id="'.$this->id.'_name" type="text" value="'.$title.'" disabled="disabled" size="60" />';

        // html for the Select button
        $html .= '<a'
            .' class="btn hasTooltip'.($value ? ' hidden' : '').'"'
            .' id="'.$this->id.'_select"'
            .' data-toggle="modal"'
            .' role="button"'
            .' href="#ModalSelect'.$modalId.'"'
            .' title="'.JHtml::tooltipText('COM_SIAK_MENUITEM_SELECT_BUTTON_TOOLTIP').'">'
            .'<span class="icon-file" aria-hidden="true"></span> '.JText::_('JSELECT')
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
        $linkIndustri = 'index.php?option=com_siak&amp;view=industries&amp;layout=modal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';
        $urlSelect = $linkIndustri.'&amp;function=jSelectIndustri_'.$this->id;

        // title to go in the modal header
        $modalTitle = JText::_('COM_SIAK_INDUSTRI_SELECT_MODAL_TITLE');

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
            .'" data-text="'.htmlspecialchars(JText::_('COM_SIAK_MENUITEM_SELECT_INDUSTRI', true), ENT_COMPAT, 'UTF-8')
            .'" value="'.$value.'" />';

        return $html;
    }

    protected function getLabel()
    {
        return str_replace($this->id, $this->id.'_id', parent::getLabel());
    }
}
