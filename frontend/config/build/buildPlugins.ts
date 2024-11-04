import {BuildOptions} from './types/config'
import {PluginOption} from 'vite'
import react from '@vitejs/plugin-react'
import symfonyPlugin from 'vite-plugin-symfony'

const buildPlugins = (args: BuildOptions): PluginOption[] => ([
	react(),
	args.isDev ? false : symfonyPlugin(),
])

export {
	buildPlugins,
}