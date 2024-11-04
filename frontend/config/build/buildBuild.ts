import {BuildOptions as ViteBuildOptions} from 'vite'
import {BuildOptions} from './types/config'

const buildBuild = (args: BuildOptions): ViteBuildOptions => ({
	outDir: args.paths.build,
	rollupOptions: {
		input: {
			app: args.paths.entry,
		},
		output: {
			dir: args.paths.build,
		},
	},
})

export {
	buildBuild,
}