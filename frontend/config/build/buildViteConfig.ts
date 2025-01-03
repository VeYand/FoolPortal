import {UserConfig} from 'vite'
import {buildBuild} from './buildBuild'
import {buildPlugins} from './buildPlugins'
import {buildResolve} from './buildResolve'
import {buildServer} from './buildServer'
import {BuildOptions} from './types/config'

const buildViteConfig = (args: BuildOptions): UserConfig => ({
	mode: args.mode,
	build: buildBuild(args),
	plugins: buildPlugins(args),
	server: buildServer(args),
	resolve: buildResolve(args),
	envDir: args.paths.envDir,
})

export {
	buildViteConfig,
}