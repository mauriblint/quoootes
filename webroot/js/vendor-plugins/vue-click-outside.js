// Vue.directive('click-outside', {
//   priority: 700,
//   bind: function (el) {
//     var self = Vue;
//     el.addEventListener('click', function (event) {event.stopPropagation()})
//     document.body.addEventListener('click', function (event) {
//         app.$emit(self.expression, event)
//     })
//   },
//
//   unbind: function (el) {
//     el.removeEventListener('click', this.stopProp)
//     document.body.removeEventListener('click',this.event)
//   },
//   // stopProp: function (event) {event.stopPropagation() }
// })
