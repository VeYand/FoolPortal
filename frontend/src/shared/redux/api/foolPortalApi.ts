import {AuthorizationApi, Configuration} from 'shared/api'
import {ApiWrapper} from './apiWrapper'

type FoolPortalApiType = {
	authorizationApi: AuthorizationApi,
}

// @ts-expect-error
const baseUrl = import.meta.env?.VITE_APP_BASE_URL
if (!baseUrl) {
	throw new Error('Base url is not configured')
}

let resultApi: FoolPortalApiType | null = null

const foolPortalApi: ApiWrapper<FoolPortalApiType> = {
	init: () => {
		resultApi = {
			authorizationApi: new AuthorizationApi(new Configuration({basePath: baseUrl})),
		}
	},
	get: () => {
		if (!resultApi) {
			throw new Error('FoolPortalApi is not initialized')
		}
		return resultApi
	},
}

export {
	foolPortalApi,
}