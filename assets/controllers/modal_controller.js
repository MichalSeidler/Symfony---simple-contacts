import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';
import $ from 'jquery';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];

    async openModal(event) {
        this.modalBodyTarget.innerHTML = 'Loading...';
        const modal = new Modal(this.modalTarget);
        modal.show();
        this.modalBodyTarget.innerHTML = await $.ajax(event.params.url);
    }
}