<template id="v-draggable-resizable-template">
	<div class="vdr" :style="style"
		:class="{
			draggable: draggable,
			resizable: resizable,
			active: enabled,
			dragging: dragging,
			resizing: resizing
		}"
		@mousedown.stop="elmDown"
		@touchstart.prevent.stop="elmDown"
		@dblclick="fillParent">
		<div v-for="handle in handles" v-if="resizable"
			class="handle"
			:key="handle"
			:class="'handle-' + handle"
			:style="{ display: enabled ? 'block' : 'none'}"
			@mousedown.stop.prevent="handleDown(handle, $event)"
			@touchstart.stop.prevent="handleDown(handle, $event)">
		</div>
		<slot></slot>
	</div>
</template>
