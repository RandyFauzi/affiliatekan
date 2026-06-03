import './bootstrap';
import Alpine from 'alpinejs';

window.copyablePanel = (copyTargetValue, successMessage) => ({
    feedbackMessage: '',
    copyTargetValue,
    successMessage,
    async copyValue() {
        try {
            await navigator.clipboard.writeText(this.copyTargetValue);
            this.feedbackMessage = this.successMessage;

            window.setTimeout(() => {
                this.feedbackMessage = '';
            }, 2200);
        } catch (copyError) {
            console.error('Failed to copy integration value.', copyError);
            this.feedbackMessage = 'Clipboard tidak tersedia di browser ini.';
        }
    },
});

window.Alpine = Alpine;

Alpine.start();
