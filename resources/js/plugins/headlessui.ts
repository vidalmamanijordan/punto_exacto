import type { App } from 'vue';
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/vue';

export default {
    install(app: App) {
        app.component('HCombobox', Combobox);
        app.component('HComboboxButton', ComboboxButton);
        app.component('HComboboxInput', ComboboxInput);
        app.component('HComboboxLabel', ComboboxLabel);
        app.component('HComboboxOption', ComboboxOption);
        app.component('HComboboxOptions', ComboboxOptions);
    },
};
