import {AuthorizationApi, Configuration, SubjectApi} from 'shared/api'
import {ApiWrapper} from './apiWrapper'

type StudentPortalApiType = {
	authorizationApi: AuthorizationApi,
	subjectApi: SubjectApi,
}

// @ts-expect-error
const baseUrl = import.meta.env?.VITE_APP_BASE_URL
if (!baseUrl) {
	throw new Error('Base url is not configured')
}

let resultApi: StudentPortalApiType | null = null

const studentPortalApi: ApiWrapper<StudentPortalApiType> = {
	init: () => {
		const configuration = new Configuration({basePath: baseUrl})
		resultApi = {
			authorizationApi: new AuthorizationApi(configuration),
			subjectApi: new SubjectApi(configuration),
		}
	},
	get: () => {
		if (!resultApi) {
			throw new Error('StudentPortalApi is not initialized')
		}
		return resultApi
	},
}

export {
	studentPortalApi,
}