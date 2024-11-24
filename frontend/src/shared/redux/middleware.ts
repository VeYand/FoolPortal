import {Middleware} from '@reduxjs/toolkit'
import {userEntitySlice} from 'entities/user'
import {LoginRoute} from 'shared/routes'

const authorizationMiddleware: Middleware = _ => next => action => {
	if (
		action.meta?.requestStatus === 'rejected'
		&& action.error?.message
		&& getStatusFromErrorMessage(action.error.message) === '401'
	) {
		next(userEntitySlice.actions.setInitialized(false))
		if (window.location.pathname !== LoginRoute.path) {
			window.location.replace(window.location.origin + LoginRoute.path)
		}
	}

	return next(action)
}

const getStatusFromErrorMessage = (message: string) => (message.split(' ').pop() as string)

export {
	authorizationMiddleware,
}