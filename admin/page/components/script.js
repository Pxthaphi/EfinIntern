import { Pane } from 'https://cdn.skypack.dev/tweakpane@4.0.4'
import gsap from 'https://cdn.skypack.dev/gsap@3.12.0'

const config = {
  debug: false,
  theme: 'light',
  css: false,
  snap: true,
  meta: true,
  content: true,
}

const drawer = document.querySelector('.drawer')
const scroller = drawer.querySelector('.drawer__scroller')
const slide = drawer.querySelector('.drawer__slide')

const ctrl = new Pane({
  title: 'Config',
  expanded: false,
})

const viewportTag = document.querySelector('meta[name="viewport"]')

const update = () => {
  document.documentElement.dataset.theme = config.theme
  document.documentElement.dataset.debug = config.debug
  document.documentElement.dataset.css = config.css
  document.documentElement.dataset.snap = config.snap
  document.documentElement.dataset.meta = config.meta
  document.documentElement.dataset.content = config.content

  viewportTag.content = `width=device-width, initial-scale=1,
      user-scalable=0, maximum-scale=1.0, ${
        config.meta ? 'interactive-widget=resizes-content' : ''
      }`
}

const sync = (event) => {
  if (
    !document.startViewTransition ||
    event.target.controller.view.labelElement.innerText !== 'Theme'
  )
    return update()
  document.startViewTransition(() => update())
}

ctrl.addBinding(config, 'css', {
  label: 'CSS only',
})
ctrl.addBinding(config, 'snap', {
  label: 'scroll-snap',
})
ctrl.addBinding(config, 'meta', {
  label: 'Meta key',
})
ctrl.addBinding(config, 'content', {
  label: 'Show content',
})

ctrl.addBinding(config, 'debug', {
  label: 'Debug',
})
ctrl.addBinding(config, 'theme', {
  label: 'Theme',
  options: {
    System: 'system',
    Light: 'light',
    Dark: 'dark',
  },
})

ctrl.on('change', sync)
update()

// Drawer mechanics
// THIS IS ALL THE PARTS FOR THE DRAWER THAT WE COVER
// close the drawer on snap change if === 0
const scrollSnapChangeSupport = 'onscrollsnapchange' in window
const scrollAnimationSupport = CSS.supports('animation-timeline: scroll()')
if (scrollSnapChangeSupport) {
  scroller.addEventListener('scrollsnapchange', () => {
    if (scroller.scrollTop === 0) {
      drawer.dataset.snapped = true
      drawer.hidePopover()
    }
  })
}

const anchor = drawer.querySelector('.drawer__anchor')
const options = {
  root: drawer,
  rootMargin: '0px 0px -1px 0px',
  threshold: 1.0,
}
let observer

let syncer
let syncs = new Array(10) // Fixed-size array
let index = 0 // Tracks the current position in the array

function addNumber(num) {
  syncs[index] = num // Place the new number at the current index
  index = (index + 1) % syncs.length // Move index forward, wrapping around if necessary
}

let frame = 0
const syncDrawer = () => {
  syncer = requestAnimationFrame(() => {
    document.documentElement.style.setProperty(
      '--closed',
      1 - scroller.scrollTop / slide.offsetHeight
    )

    if (new Set(syncs).size === 1 && syncs[0] === slide.offsetHeight) {
      frame++
    }
    if (frame >= 10) {
      frame = 0
      syncs = new Array(10)
      scroller.addEventListener('scroll', scrollDriver, { once: true })
    } else {
      addNumber(scroller.scrollTop)
      syncDrawer()
    }
  })
}

const scrollDriver = () => {
  syncDrawer()
}

const callback = (entries) => {
  const { isIntersecting, intersectionRatio } = entries[0]
  const isVisible = intersectionRatio === 1

  if (
    !isVisible &&
    !isIntersecting &&
    scroller.scrollTop - window.visualViewport.offsetTop <
      slide.offsetHeight * 0.5
  ) {
    drawer.dataset.snapped = true
    drawer.hidePopover()
    observer.disconnect()
  }
}

const handleOut = (event) => {
  if (!drawer.contains(event.target) || !event.target) {
    window.removeEventListener('focus', handleOut, true)
    drawer.hidePopover()
  }
}

// reset the drawer once closed
drawer.addEventListener('toggle', (event) => {
  if (config.css) return
  if (event.newState === 'closed') {
    drawer.dataset.snapped = false
    scroller.removeEventListener('scroll', scrollDriver)
    if (syncer) cancelAnimationFrame(syncer)
    document.documentElement.style.removeProperty('--closed')
    window.removeEventListener('focus', handleOut, true)
  }
  if (event.newState === 'open' && !scrollSnapChangeSupport) {
    if (!observer) observer = new IntersectionObserver(callback, options)
    observer.observe(anchor)
  }
  if (event.newState === 'open' && !scrollAnimationSupport) {
    scroller.addEventListener('scroll', scrollDriver, { once: true })
  }
  if (event.newState === 'open') {
    window.addEventListener('focus', handleOut, true)
  }
})

const attachDrag = (element) => {
  let startY = 0
  let drag = 0
  let scrollStart

  const reset = () => {
    startY = drag = 0
    const top = scroller.scrollTop < scrollStart * 0.5 ? 0 : scrollStart

    const handleScroll = () => {
      if (scroller.scrollTop === top) {
        document.documentElement.dataset.dragging = false
        scroller.removeEventListener('scroll', handleScroll)
      }
    }
    scroller.addEventListener('scroll', handleScroll)

    scroller.scrollTo({
      top,
      behavior: 'smooth',
    })
    handleScroll()
  }

  const handle = ({ y }) => {
    drag += Math.abs(y - startY)
    scroller.scrollTo({
      top: scrollStart - (y - startY),
      behavior: 'instant',
    })
  }
  const teardown = (event) => {
    if (event.target.tagName !== 'BUTTON') {
      reset()
    }
    document.removeEventListener('mousemove', handle)
    document.removeEventListener('mouseup', teardown)
  }
  const activate = ({ y }) => {
    startY = y
    scrollStart = scroller.scrollTop
    document.documentElement.dataset.dragging = true
    document.addEventListener('mousemove', handle)
    document.addEventListener('mouseup', teardown)
  }
  element.addEventListener('click', (event) => {
    if (drag > 5) event.preventDefault()
    reset()
  })
  element.addEventListener('mousedown', activate)
}
// Only happens on mousemove so we are only affecting the scroll position
attachDrag(drawer)

// Handle VisualViewport changes for iOS
const handleResize = () => {
  document.documentElement.style.setProperty(
    '--sw-keyboard-height',
    window.visualViewport.offsetTop
  )
}
window.visualViewport?.addEventListener('resize', handleResize)
// THERE REALLY ISN'T THAT MUCH TO BE HONEST

// THIS PART IS IRRELEVANT BUT EMOJI REACTION BARS ARE FUN
const canvas = document.querySelector('canvas')
const ctx = canvas.getContext('2d')
const reactions = document.querySelector('.reactions')
const syncCanvas = () => {
  const dpr = window.devicePixelRatio || 1
  canvas.width = window.innerWidth * dpr
  canvas.height = window.innerHeight * dpr
  ctx.scale(dpr, dpr)
}
syncCanvas()
window.addEventListener('resize', syncCanvas)

const live = []

const render = () => {
  ctx.clearRect(0, 0, canvas.width, canvas.height)
  for (const reaction of live) {
    ctx.font = `${reaction.size}px serif`
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.globalAlpha = reaction.opacity
    ctx.fillText(reaction.symbol, reaction.x, reaction.y)
  }
}

// Can do this two ways
// 1. On basic click
// 2. Firehose mode
const fire = ({ x, y, target }) => {
  if (target.dataset.emoji) {
    if (live.length === 0) {
      gsap.ticker.add(render)
    }
    const reaction = {
      id: crypto.randomUUID(),
      x: x,
      opacity: 1,
      y: y + window.visualViewport.offsetTop,
      symbol: target.dataset.emoji,
      size: gsap.utils.random(24, 48),
      active: true,
    }
    live.push(reaction)
    gsap
      .timeline({
        onComplete: () => {
          reaction.active = false
          if (live.filter((r) => r.active).length === 0) {
            gsap.ticker.remove(render)
            live.length = 0
          }
        },
      })
      .from(reaction, {
        size: 0,
      })
      .to(
        reaction,
        {
          x: `-=${gsap.utils.random(-50, 50, 1)}`,
          yoyo: true,
          repeat: 2,
        },
        0
      )
      .to(
        reaction,
        {
          ease: 'power1.out',
          duration: 1,
          y: `-=${gsap.utils.random(
            slide.offsetHeight * 0.5,
            slide.offsetHeight * 1.5,
            1
          )}`,
        },
        0
      )
      .to(
        reaction,
        {
          opacity: 0,
          duration: 0.25,
        },
        '>-0.25'
      )
  }
}

const douse = () => {
  window.removeEventListener('pointermove', fire)
  window.removeEventListener('pointerup', douse)
}

const activate = (event) => {
  fire(event)
  window.addEventListener('pointermove', fire)
  window.addEventListener('pointerup', douse)
}

reactions.addEventListener('pointerdown', activate)
// BORING MODE:: reactions.addEventListener('click', fire)

// Silly form stuff here...
const form = document.querySelector('form')
const input = form.querySelector('input')
const handleForm = (event) => {
  event.preventDefault()
  const content = `"${input.value}"

saw @jh3yy's drawer demo and this is what I thought...

check it out on @CodePen: codepen.io/jh3y/pen/LYwdMKN
  `
  window.open(
    `https://x.com/intent/tweet?text=${encodeURIComponent(content)}`,
    '_blank'
  )
}
form.addEventListener('submit', handleForm)
