import * as path from 'path'
// @ts-ignore
import url from 'url'
import {ConfigEnv} from 'vite'
import {BuildAliases, BuildMode, BuildPaths} from './config/build/types/config'
import {buildViteConfig} from './config/build/buildViteConfig'

// @ts-ignore
const __filename = url.fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

export default (configEnv: ConfigEnv) => {
	const mode = (configEnv.mode ?? 'development') as BuildMode
	const isDev = mode === 'development'
	const paths: BuildPaths = {
		html: '',
		build: path.resolve(__dirname, '../backend', 'public', 'build'),
		entry: path.resolve(__dirname, 'src', 'main.tsx'),
		envDir: path.resolve(__dirname, '../backend', 'docker', 'nginx'),
	}

	const aliases: BuildAliases = {
		shared: path.resolve(__dirname, 'src', 'shared'),
		pages: path.resolve(__dirname, 'src', 'pages'),
		widgets: path.resolve(__dirname, 'src', 'widgets'),
		features: path.resolve(__dirname, 'src', 'features'),
		entities: path.resolve(__dirname, 'src', 'entities'),
		app: path.resolve(__dirname, 'src', 'app'),
	}

	return buildViteConfig({
		isDev,
		mode,
		paths,
		aliases,
		port: 3000,
	})
}
