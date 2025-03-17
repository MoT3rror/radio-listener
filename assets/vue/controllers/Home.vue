<template>
    <Layout>
        <v-date-input
            label="Select Date to View"
            v-model="dateSelected"
        />

        <v-tabs
            v-model="radioSelected"
            color="primary"
            :items="radios"
        >
            <template v-slot:tab="{ item: radio }">
                <v-tab
                    :text="radio.name"
                    :value="radio.id"
                ></v-tab>
            </template>
            <template v-slot:item="{ item: radio }" :ref="'radio-info' + radio.id">
                <v-tabs-window-item
                    :value="radio.id"
                    class="pa-4"
                >
                    <RadioRecordings :radio="radio" :date="dateSelected" />
                </v-tabs-window-item>
            </template>
        </v-tabs>
    </Layout>
</template>

<script setup>
    import { ref, defineProps } from 'vue';
    import Layout from './../Layout.vue';
    import RadioRecordings from './../components/RadioRecordings.vue';
    import { VDateInput } from 'vuetify/labs/VDateInput'
    const props = defineProps({
        radios: Array,
    });
    const radioSelected = ref(props.radios[0].id);
    const dateSelected = ref(new Date());
</script>
