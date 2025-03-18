<script setup>
import { useTheme } from 'vuetify'

const theme = useTheme()

function toggleTheme () {
    theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}
</script>

<template>
    <v-responsive class="border rounded">
        <v-app :theme="theme.global.name.value">
            <v-app-bar class="px-3">
                <v-app-bar-title>Radio Listener</v-app-bar-title>

                <v-spacer></v-spacer>
  
                <v-btn
                    :prepend-icon="theme === 'light' ? 'mdi-weather-sunny' : 'mdi-weather-night'"
                    text="Toggle Theme"
                    slim
                    @click="toggleTheme"
                ></v-btn>
            </v-app-bar>
  
            <v-main>
                <v-container>
                    <slot></slot>
                </v-container>
            </v-main>
        </v-app>
    </v-responsive>
</template>
