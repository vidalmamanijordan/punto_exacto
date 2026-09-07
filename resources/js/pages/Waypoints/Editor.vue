<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import axios from 'axios'
import 'leaflet/dist/leaflet.css'
import L from 'leaflet'

import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png'
import markerIcon from 'leaflet/dist/images/marker-icon.png'
import markerShadow from 'leaflet/dist/images/marker-shadow.png'

delete (L.Icon.Default.prototype as any)._getIconUrl
L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
})

interface Campus {
  id: number
  name: string
  latitude: string
  longitude: string
}

interface Waypoint {
  id: number
  campus_id: number
  name: string | null
  latitude: string
  longitude: string
  is_active: boolean
}

interface Path {
  id: number
  from_waypoint_id: number
  to_waypoint_id: number
  distance: number
  is_bidirectional: boolean
  is_active: boolean
}

const campuses = ref<Campus[]>([])
const selectedCampusId = ref<number | null>(null)
const loading = ref(true)

const waypoints = ref<Waypoint[]>([])
const paths = ref<Path[]>([])

// Id del waypoint marcado como "origen" mientras el usuario
// arma una conexión (null = no hay selección activa).
const pendingConnectionId = ref<number | null>(null)

const mapContainer = ref<HTMLDivElement | null>(null)
let map: L.Map | null = null
let graphLayer: L.LayerGroup | null = null
let highlightLayer: L.LayerGroup | null = null

// Referencia a los marcadores de Leaflet por id de waypoint,
// para poder abrir/actualizar popups puntuales sin redibujar todo.
let markersById: Record<number, L.Marker> = {}

onMounted(async () => {
  await loadCampuses()
  initMap()
  await loadGraph()
})

onBeforeUnmount(() => {
  map?.remove()
})

watch(selectedCampusId, async (newCampusId) => {
  if (newCampusId === null || !map) return

  cancelSelection()

  const campus = campuses.value.find((c) => c.id === newCampusId)

  if (campus) {
    map.setView(
      [Number(campus.latitude), Number(campus.longitude)],
      17
    )
  }

  await loadGraph()
})

async function loadCampuses() {
  try {
    const { data } = await axios.get('/api/campuses')
    campuses.value = data.data ?? data

    if (campuses.value.length > 0) {
      selectedCampusId.value = campuses.value[0].id
    }
  } catch (error) {
    console.error('Error cargando campus', error)
  } finally {
    loading.value = false
  }
}

function initMap() {
  if (!mapContainer.value || campuses.value.length === 0) return

  const firstCampus = campuses.value[0]

  map = L.map(mapContainer.value).setView(
    [Number(firstCampus.latitude), Number(firstCampus.longitude)],
    17
  )

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19,
  }).addTo(map)

  graphLayer = L.layerGroup().addTo(map)
  highlightLayer = L.layerGroup().addTo(map)

  // Conecta los botones "Eliminar" dentro de los popups
  // cada vez que uno se abre. Debe registrarse aquí, sobre
  // el "map" ya inicializado (antes estaba fuera de esta
  // función y "map" todavía era null en ese momento).
  map.on('popupopen', attachPopupHandlers)

  // Clic en zona vacía del mapa = crear un waypoint nuevo ahí.
  map.on('click', (e: L.LeafletMouseEvent) => {
    handleMapClick(e.latlng)
  })
}

async function loadGraph() {
  if (!selectedCampusId.value) return

  try {
    const [waypointsRes, pathsRes] = await Promise.all([
      axios.get('/api/waypoints', {
        params: { campus_id: selectedCampusId.value },
      }),
      axios.get('/api/paths', {
        params: { campus_id: selectedCampusId.value },
      }),
    ])

    waypoints.value = waypointsRes.data.data ?? waypointsRes.data
    paths.value = pathsRes.data.data ?? pathsRes.data

    renderGraph()
  } catch (error) {
    console.error('Error cargando el grafo de waypoints', error)
  }
}

function renderGraph() {
  if (!graphLayer) return

  graphLayer.clearLayers()
  markersById = {}

  for (const path of paths.value) {
    const from = waypoints.value.find((w) => w.id === path.from_waypoint_id)
    const to = waypoints.value.find((w) => w.id === path.to_waypoint_id)

    if (!from || !to) continue

    const line = L.polyline(
      [
        [Number(from.latitude), Number(from.longitude)],
        [Number(to.latitude), Number(to.longitude)],
      ],
      { color: '#2563eb', weight: 3 }
    ).addTo(graphLayer)

    line.bindPopup(buildPathPopup(path))
  }

  for (const waypoint of waypoints.value) {
    const marker = L.marker([
      Number(waypoint.latitude),
      Number(waypoint.longitude),
    ]).addTo(graphLayer)

    marker.bindPopup(buildWaypointPopup(waypoint))

    marker.on('click', () => {
      handleMarkerClick(waypoint)
    })

    markersById[waypoint.id] = marker
  }
}

/**
 * Construye el HTML del popup de un waypoint, con un botón
 * de eliminar que se conecta después de que el popup se abre.
 */
function buildWaypointPopup(waypoint: Waypoint): string {
  return `
    <div class="text-sm">
      <p class="font-medium">${waypoint.name ?? `Waypoint #${waypoint.id}`}</p>
      <button
        data-delete-waypoint="${waypoint.id}"
        class="text-red-600 text-xs mt-1 underline"
      >
        Eliminar waypoint
      </button>
    </div>
  `
}

function buildPathPopup(path: Path): string {
  return `
    <div class="text-sm">
      <p class="font-medium">${Math.round(path.distance)}m</p>
      <button
        data-delete-path="${path.id}"
        class="text-red-600 text-xs mt-1 underline"
      >
        Eliminar camino
      </button>
    </div>
  `
}

function attachPopupHandlers(e: L.PopupEvent) {
  const container = e.popup.getElement()
  if (!container) return

  const deleteWaypointBtn = container.querySelector('[data-delete-waypoint]')
  deleteWaypointBtn?.addEventListener('click', () => {
    const id = Number(deleteWaypointBtn.getAttribute('data-delete-waypoint'))
    deleteWaypoint(id)
  })

  const deletePathBtn = container.querySelector('[data-delete-path]')
  deletePathBtn?.addEventListener('click', () => {
    const id = Number(deletePathBtn.getAttribute('data-delete-path'))
    deletePath(id)
  })
}

async function handleMapClick(latlng: L.LatLng) {
  if (!selectedCampusId.value) return

  const name = window.prompt('Nombre del waypoint (opcional):')

  // Si el usuario presiona "Cancelar" en el prompt, no creamos nada.
  if (name === null) return

  try {
    await axios.post('/api/waypoints', {
      campus_id: selectedCampusId.value,
      name: name || null,
      latitude: latlng.lat,
      longitude: latlng.lng,
    })

    await loadGraph()
  } catch (error) {
    console.error('Error creando waypoint', error)
    alert('No se pudo crear el waypoint.')
  }
}

function handleMarkerClick(waypoint: Waypoint) {
  if (pendingConnectionId.value === null) {
    // Primer clic: marca este waypoint como origen.
    pendingConnectionId.value = waypoint.id
    showHighlight(waypoint)
    return
  }

  if (pendingConnectionId.value === waypoint.id) {
    // Clic sobre el mismo waypoint: cancela la selección.
    cancelSelection()
    return
  }

  // Segundo clic sobre otro waypoint: crea el path.
  createPath(pendingConnectionId.value, waypoint.id)
}

async function createPath(fromId: number, toId: number) {
  try {
    await axios.post('/api/paths', {
      from_waypoint_id: fromId,
      to_waypoint_id: toId,
    })

    cancelSelection()
    await loadGraph()
  } catch (error: any) {
    const message =
      error.response?.data?.message ??
      'No se pudo crear el camino entre estos waypoints.'

    alert(message)
    cancelSelection()
  }
}

function showHighlight(waypoint: Waypoint) {
  if (!highlightLayer) return

  highlightLayer.clearLayers()

  L.circleMarker(
    [Number(waypoint.latitude), Number(waypoint.longitude)],
    {
      radius: 14,
      color: '#f97316',
      weight: 3,
      fill: false,
    }
  ).addTo(highlightLayer)
}

function cancelSelection() {
  pendingConnectionId.value = null
  highlightLayer?.clearLayers()
}

async function deleteWaypoint(id: number) {
  if (!confirm('¿Eliminar este waypoint? También se eliminarán sus caminos conectados.')) {
    return
  }

  try {
    await axios.delete(`/api/waypoints/${id}`)
    await loadGraph()
  } catch (error) {
    console.error('Error eliminando waypoint', error)
    alert('No se pudo eliminar el waypoint.')
  }
}

async function deletePath(id: number) {
  if (!confirm('¿Eliminar este camino?')) return

  try {
    await axios.delete(`/api/paths/${id}`)
    await loadGraph()
  } catch (error) {
    console.error('Error eliminando path', error)
    alert('No se pudo eliminar el camino.')
  }
}
</script>

<template>
  <AppLayout title="Editor de Waypoints">
    <div class="p-6 space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-800">
          Editor visual de rutas
        </h1>

        <select
          v-model="selectedCampusId"
          class="border border-gray-300 rounded-md text-sm p-2"
        >
          <option v-for="campus in campuses" :key="campus.id" :value="campus.id">
            {{ campus.name }}
          </option>
        </select>
      </div>

      <div v-if="loading" class="text-sm text-gray-500">
        Cargando campus...
      </div>

      <div
        v-if="pendingConnectionId !== null"
        class="flex items-center justify-between bg-orange-50 text-orange-700 text-sm px-3 py-2 rounded-md"
      >
        <span>Selecciona el segundo waypoint para conectar el camino.</span>
        <button class="underline" @click="cancelSelection">Cancelar</button>
      </div>

      <div ref="mapContainer" class="w-full h-150 rounded-lg border" />

      <p class="text-xs text-gray-400">
        {{ waypoints.length }} waypoints · {{ paths.length }} caminos en este campus.
        Haz clic en el mapa para crear un waypoint; haz clic en dos waypoints seguidos para conectarlos.
      </p>
    </div>
  </AppLayout>
</template>
