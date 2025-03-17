<script setup>
    import { ref, watchEffect } from 'vue';
    
    const { radio, date } = defineProps({
        radio: Object,
        date: Date,
    })
    const recordings = ref([])

    watchEffect(() => {
        fetch(`/api/radio/${radio.id}/recordings?date=${date.toISOString()}`)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                recordings.value = data.recordings
            })
    })
</script>

<template>
    <h2>Radio Recordings</h2>
    <p>Radio: {{ radio.name }}</p>
    <p>Date: {{ date }}</p>
    <p>Recordings: {{ recordings }}</p>
</template>
