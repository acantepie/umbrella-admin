{% extends "@UmbrellaAdmin/edit_modal.html.twig" %}
{% block modal_title %}
    <h4 class="modal-title">{{ entity.id ? ( 'action.edit_<?php echo $i18n_id; ?>' | trans) : ( 'action.add_<?php echo $i18n_id; ?>' | trans) }}</h4>
{% endblock %}