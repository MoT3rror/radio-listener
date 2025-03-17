<template>
    <Layout>
        <v-tabs
            v-model="tab"
            color="primary"
            :items="radios"
        >
            <template v-slot:tab="{ item: radio }">
                <v-tab
                    :text="radio.name"
                    :value="radio.id"
                    @click="loadRadioInfo(radio)"
                ></v-tab>
            </template>
            <template v-slot:item="{ item: radio }" :ref="'radio-info' + radio.id">
                <v-tabs-window-item
                    :value="radio.id"
                    class="pa-4"
                >
                    tab info for {{ radio.name }}
                </v-tabs-window-item>
            </template>
        </v-tabs>
    </Layout>
</template>

<script setup>
    import { ref, defineProps } from 'vue';
    import Layout from './../Layout.vue';
    const props = defineProps({
        radios: Array,
    });
    const tab = ref(props.radios[0].id);

    let loadRadioInfo = radio => {
        
    }
</script>
