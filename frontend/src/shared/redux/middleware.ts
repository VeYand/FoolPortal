import {Middleware} from '@reduxjs/toolkit'
import {LoginRoute} from '../routes'

const authorizationMiddleware: Middleware = _ => next => action => {
	if (
		action.meta?.requestStatus === 'rejected'
		&& action.error?.message
		&& getStatusFromErrorMessage(action.error.message) === '401'
		&& window.location.pathname !== LoginRoute.path
	) {
		location.reload()
	}

	return next(action)
}

const getStatusFromErrorMessage = (message: string) => (message.split(' ').pop() as string)

export {
	authorizationMiddleware,
}