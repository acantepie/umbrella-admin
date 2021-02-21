import Component from "umbrella_core/core/Component";

export default class Notification extends Component {

    constructor($view) {
        super($view);

        this.refreshUrl = $view.data('refresh-url');
        this.pollInterval = $view.data('poll-interval'); // second
        this.refreshXhr = null;

        this._bind();
    }

    _bind() {
        this.$view.on('shown.bs.dropdown', () => {
            this._refresh(this.pollInterval >= 1); // refresh only if pollInterval is 1s or more
        });
    }

    /**
     * Refresh Notifications
     */
    _refresh(poll = true) {
        if (this.refreshXhr) {
            this.refreshXhr.abort();
        }

        if (this._isOpen()) {
            $.get(this.refreshUrl, (response) => {
                this._renderList(response);


                if (poll) {
                    setTimeout(() => {
                        this._refresh()
                    }, this.pollInterval * 1000);
                }
            });
        }
    }

    /**
     * Render list of notifications
     */
    _renderList(response) {
        const $list = this.$view.find('.js-notification-list .simplebar-content');
        $list.html('');

        if (response.notifications) {

            for (let notification of response.notifications) {
                const tpl = this._getTemplate(notification.template);
                if (tpl) {
                    $list.append(mustache.render(tpl, notification.data));
                }
            }


        } else if (response.empty) {
            const tpl = this._getTemplate(response.empty.template);
            if (tpl) {
                $list.append(mustache.render(tpl, response.empty.data));
            }
        }
    }

    _isOpen() {
        return this.$view.find('.dropdown-menu').hasClass('show');
    }

    _getTemplate(template) {
        if ($(template).length) {
            return $(template).html();
        } else {
            console.warn(`No template found with selector "${template}".`);
            return false;
        }
    }
}
