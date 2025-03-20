<script setup>
    import { computed, ref, watchEffect } from 'vue';
    
    const { radio, date } = defineProps({
        radio: Object,
        date: Date,
    })
    const recordings = ref([])
    const opened = ref(true)
    const loading = ref(false)

    let recordsByHour = computed(() => {
        let map = [...new Array(24)].map(x => [])

        recordings.value.forEach(element => {
            map[(new Date(element.startTime).getHours())].push(element)
        })

        return map
    })

    watchEffect(() => {
        loading.value = true
        fetch(`/api/radio/${radio.id}/recordings?date=${date.toISOString()}`)
            .then(response => response.json())
            .then(data => {
                recordings.value = data.data
                loading.value = false
            })
    })
</script>

<template>
    <h2>Current Broadcast</h2>
    <p>
        <audio controls preload="none">
            <source
                :src="radio.broadcast_listen_url"
                type="audio/mpeg"
            >
        </audio>
    </p>

    <h2>Radio Recordings</h2>

    <div v-if="loading">
        <v-progress-linear indeterminate></v-progress-linear>
    </div>

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
                    :title="new Date(recording.startTime).toLocaleTimeString() + ' - ' + new Date(recording.endTime).toLocaleTimeString()"
                    :value="recording.id"
                >
                    <pre>{{ recording.voiceToText }}</pre>
    
                    <audio controls preload="none">
                        <source
                            :src="recording.audioFile.path" 
                            type="audio/mpeg"
                        >
                    </audio>
                </v-list-item>
            </v-list-group>
        </v-list>
    </template>
</template> 

<style lang="css">
.v-list-item-title {
    font-weight: bold;
}
</style>