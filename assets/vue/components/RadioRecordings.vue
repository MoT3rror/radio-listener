<script setup>
    import { computed, ref, watchEffect } from 'vue';
    
    const { radio, date } = defineProps({
        radio: Object,
        date: Date,
    })
    const recordings = ref([])
    const opened = ref(true)

    let recordsByHour = computed(() => {
        let map = [...new Array(24)].map(x => [])

        recordings.value.forEach(element => {
            map[(new Date(element.startTime).getHours())].push(element)
        })

        return map
    })

    watchEffect(() => {
        fetch(`/api/radio/${radio.id}/recordings?date=${date.toISOString()}`)
            .then(response => response.json())
            .then(data => {
                recordings.value = data.data
            })
    })
</script>

<template>
    <h2>Radio Recordings</h2>

    <template v-for="(recordings, index) in recordsByHour" :key="index">
        <v-list v-if="recordings.length > 0">
            <v-list-group :value="index">
                <v-list-subheader
                    title="Hourly Recording"
                >
                    audio controls
                </v-list-subheader>
                <hr />
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        :title="index"
                    ></v-list-item>
                </template>
                <v-list-item
                    v-for="(recording, i) in recordings"
                    :key="recording.id"
                    :title="recording.startTime + ' - ' + recording.endTime"
                    :value="recording.id"
                >
                    <v-list-item-subtitle>
                        <audio controls>
                            <source
                                :src="recording.audioFile.path" 
                                type="audio/mpeg"
                                preload="none"
                            >
                        </audio>
                    </v-list-item-subtitle>
                </v-list-item>
            </v-list-group>
        </v-list>
    </template>
</template> 
